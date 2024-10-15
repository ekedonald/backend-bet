<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Mail\TransactionMail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AccountService
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function deposit($amount, $payment): Transaction
    {
        $this->user->balance = $this->user->balance + $amount;
        $this->user->save();

        $transaction = $this->createTransaction(AppConfig::TRANSACTION_TYPE_CREDIT, $amount, $payment);

        return $transaction;
    }

    public function initTransaction($amount, $payment): Transaction
    {
        $transaction = $this->createTransaction(AppConfig::TRANSACTION_TYPE_CREDIT, $amount, $payment);

        return $transaction;
    }

    public function withdraw($amount): Transaction
    {
        $this->user->balance = $this->user->balance - $amount;
        $this->user->save();
        $payment_object = [];

        $transaction = $this->createTransaction(AppConfig::TRANSACTION_TYPE_DEBIT, $amount, $payment_object);

        return $transaction;
    }

    public function placeBet(float $amount): Transaction
    {
        if ($amount > $this->user->balance) {
            throw new InsufficientBalanceException();
        }

        $this->user->balance -= $amount;
        $this->user->save();

        $payment_object = [];
        $transaction = $this->createTransaction(AppConfig::TRANSACTION_TYPE_PLACE_BET, $amount, $payment_object);

        return $transaction;
    }

    public function creditBet(float $amount, string $credit = AppConfig::TRANSACTION_TYPE_PLACE_BET_WON): Transaction
    {

        $this->user->balance += $amount;
        $this->user->save();

        $payment_object = [];
        $transaction = $this->createTransaction($credit, $amount, $payment_object);

        return $transaction;
    }

    public function status(Transaction $transaction): Transaction
    {
        $status = 'completed';
        $transaction->status = $status;

        return $transaction;
    }

    public function getStatus(string $type)
    {
        if ($type === AppConfig::TRANSACTION_TYPE_CREDIT || $type === AppConfig::TRANSACTION_TYPE_DEBIT) {
            return AppConfig::TRANSACTION_STATUS_WAITING;
        }

        return AppConfig::TRANSACTION_STATUS_FINISHED;
    }

    public function createTransaction(
        string $type,
        $amount,
        $payment_object,
        bool $sendNotification = false
    ): Transaction {
        $transaction = new Transaction();
        $transaction->user_id = $this->user->id;
        $transaction->amount = $amount;
        $transaction->payment_object = json_encode($payment_object);
        $transaction->reference_no = $payment_object['payment_id'] ?? null;
        $transaction->type = $type;
        $transaction->status = $this->getStatus($type);
        $transaction->save();
        $transaction->status = $this->getStatus($type);
        $transaction->sending_currency = AppConfig::DEFAULT_CURRENCY;
        if ($sendNotification) {
            Mail::send(new TransactionMail($this->user, $transaction));
        }

        return $transaction;
    }
}
