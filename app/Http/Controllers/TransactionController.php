<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\ApproveWithdrawWeb;
use App\Models\Transaction;
use App\Services\AppConfig;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(20);

        return view("transactions.index", ['transactions' => $transactions]);

    }
    
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->isPending = $transaction->status == AppConfig::TRANSACTION_STATUS_WAITING;
        return view("transactions.show", ['transaction' => $transaction]);
    }

    public function approve(ApproveWithdrawWeb $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);
        $transaction->status = AppConfig::TRANSACTION_STATUS_FINISHED;
        $transaction->save();

        return redirect()->back()->with('success', 'Withdraw has been marked as finished');
    }

    public function getWithdraws()
    {
        $transactions = Transaction::where('type', AppConfig::TRANSACTION_TYPE_DEBIT)
            ->where('status', AppConfig::TRANSACTION_STATUS_WAITING)
            ->latest()
            ->paginate();
        return view("transactions.withdraw", ['transactions' => $transactions]);
    }
}