<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public $transactionFilter = ['status', 'type', 'user_id', 'transaction_id', 'created_at'];
    
    public function index(Request $request){

        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('paginate', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');


            $transactions = Transaction::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%');
            })
            ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->transactionFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Withdraw was successful',
                'transactions' => $transactions
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUserTransactions(PaginateRequest $request){
        try {
            $userId = auth()->id();
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) ? $request->get('paginate', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');


            $transactions = Transaction::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('id', 'like', '%' . $search . '%');
            })
            ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->transactionFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Transaction list retrived',
                'transactions' => $transactions
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUserTransaction($id){
        $transaction = auth()->user()->transactions()->where('id', $id)->first();
        if(!$transaction){
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Transaction cannot be found',
            ], Response::HTTP_BAD_REQUEST);
        }
        return ResponseController::response(true,[
            ResponseController::MESSAGE => 'transaction retrived',
            'transaction' => $transaction
        ], Response::HTTP_OK);
    }

    public function show($id){
        $transaction = Transaction::where('id', $id)->first();
        if(!$transaction){
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Transaction cannot be found',
            ], Response::HTTP_BAD_REQUEST);
        }
        return ResponseController::response(true,[
            ResponseController::MESSAGE => 'transaction retrived',
            'transaction' => $transaction
        ], Response::HTTP_OK);
    }


}
