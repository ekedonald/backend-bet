<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Ticker\CreateTickerRequest;
use App\Models\Ticker;
use App\Models\Token;
use Exception;

class TickerController extends Controller
{
    public $tickerFilter = [];
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

            $tickers = Ticker::with('base_token', 'target_token')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->tickerFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Ticker list gotten',
                'tickers' => $tickers,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTickerRequest $request)
    {
        try {
            $base = Token::where('id', $request->base_token_id)->first();
            if(!$base){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'base token is not valid',
                ], Response::HTTP_NOT_FOUND);
            }
            $target = Token::where('id', $request->target_token_id)->first();
            if(!$target){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'target token is not valid',
                ], Response::HTTP_NOT_FOUND);
            }
            $ticker = new ticker();
            $ticker->base_token_id = $base->id;
            $ticker->target_token_id = $target->id;
            $ticker->name = $base->name.'/'.$target->name;
            $ticker->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Ticker has been created',
                'ticker' => $ticker
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticker = Ticker::with('base_token', 'target_token')->where('id', $id)->first();
        if(!$ticker){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'ticker not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return ResponseController::response(true,[
            'ticker' => $ticker
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTickerRequest $request, $id)
    {
        try {
            $base = Token::where('id', $request->base_token_id)->first();
            if(!$base){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'base token is not valid',
                ], Response::HTTP_NOT_FOUND);
            }
            $target = Token::where('id', $request->target_token_id)->first();
            if(!$target){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'target token is not valid',
                ], Response::HTTP_NOT_FOUND);
            }
            $ticker = Ticker::findOrFail($id);
            $ticker->base_token_id = $base->id;
            $ticker->target_token_id = $target->id;
            $ticker->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Ticker has been created',
                'ticker' => $ticker
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ticker = Ticker::findOrFail($id);
            $ticker->delete();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'ticker has been deleted'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}
