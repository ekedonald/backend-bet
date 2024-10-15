<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\DepositRequest;
use App\Http\Requests\Transaction\GetTransactionStatusRequest;
use App\Http\Requests\Transaction\WithdrawRequest;
use App\Models\Transaction;
use App\Services\AccountService;
use App\Services\AppConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getStatus(GetTransactionStatusRequest $request){
        try {
            $transaction = Transaction::where('id', $request->payment_id)->first();
            if(!$transaction){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'Transaction could not be found'
                ], Response::HTTP_BAD_REQUEST);
            }
            DB::beginTransaction();
            $user = auth()->user();

            $payment = $this->transactionStatus($transaction->reference_no);

            $paymentObject = $transaction->payment_object;

            $paymentObject->payment_status = $payment['payment_status'];
            $paymentObject->actually_paid = $payment['actually_paid'];

            $transaction->status = $payment['payment_status'];
            $transaction->payment_object = json_encode($paymentObject);

            $transaction->save();
            if($payment['payment_status'] == AppConfig::TRANSACTION_STATUS_FINISHED){
    
                $account = new AccountService($user);
                $transaction = $account->deposit($transaction->amount, $payment);
            }

            DB::commit();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Deposit was successful',
                'transaction' => $transaction
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Deposit could not be completed try again because '.$th->getMessage(),
            ], Response::HTTP_BAD_GATEWAY);
        }
    }
    public function deposit(DepositRequest $request){
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $payment = $this->initTransaction($request->amount, $request->currency);

            if ($payment === false) {
                DB::rollBack();
                return ResponseController::response(false, [
                    ResponseController::MESSAGE => 'Deposit could not be completed due to a payment error. Please try a different currency.',
                ], Response::HTTP_BAD_GATEWAY);
            }
            
            $account = new AccountService($user);
            $transaction = $account->initTransaction($request->amount, $payment);
            DB::commit();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Deposit was successful',
                'transaction' => $transaction
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Deposit could not be completed try again because '.$th->getMessage(),
            ], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function withdraw(WithdrawRequest $request){
        $user = auth()->user();
        if($user->balance < $request->amount){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Insuffient balance'
            ], Response::HTTP_BAD_REQUEST);
        }
        $account = new AccountService($user);
        $transaction = $account->withdraw($request->amount);
        return ResponseController::response(true,[
            ResponseController::MESSAGE => 'Withdraw was successful',
            'transaction' => $transaction
        ], Response::HTTP_OK);
    }

    public function getTransactions(){
        $transactions = Transaction::where('user_id')->get();
        return ResponseController::response(true,[
            ResponseController::MESSAGE => 'Transaction List retrived',
            'transactions' => $transactions
        ], Response::HTTP_OK);
    }

}
