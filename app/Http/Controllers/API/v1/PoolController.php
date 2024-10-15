<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\PaginateRequest;
use App\Models\Pool;
use Exception;

class PoolController extends Controller
{
    public $poolFilter = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            $pools = Pool::with('ticker', 'ticker.base_token', 'ticker.target_token', 'bets')
                ->where('closed_at', '>', now())
                ->where('settled_at', null)
                ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->poolFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Pool list gotten',
                'pools' => $pools,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $pool = Pool::with('ticker', 'ticker.base_token', 'ticker.target_token', 'bets', 'bets.user:id,first_name,last_name,avatar')
            ->where('closed_at', '>', now())
            ->where('id', $id)->first();
        if(!$pool){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'pool not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return ResponseController::response(true,[
            'pool' => $pool
        ], Response::HTTP_OK);
    }
}
