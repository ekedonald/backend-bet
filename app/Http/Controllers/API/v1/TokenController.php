<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Token\CreateTokenRequest;
use App\Models\Token;
use Exception;

class TokenController extends Controller
{
    public $tokenFilter = [];
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

            $tokens = Token::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->tokenFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Token list gotten',
                'tokens' => $tokens,
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
    public function store(CreateTokenRequest $request)
    {
        try {
            // $response = $this->getCoinFromExternalApi($request->name);
            $token = new Token();
            $token->name = $request->name;
            $token->symbol = $request->symbol;
            $token->tokenObject = json_encode([]);
            $token->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Token has been created',
                'token' => $token
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
        $token = Token::where('id', $id)->first();
        if(!$token){
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'token not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return ResponseController::response(true,[
            'token' => $token
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTokenRequest $request, $id)
    {
        try {
            $token = Token::findOrFail($id);
            $token->name = $request->name;
            $token->symbol = $request->symbol;
            $token->tokenObject = json_encode([]);
            $token->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Token has been updated',
                'token' => $token
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
            $token = Token::findOrFail($id);
            $token->delete();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Token has been deleted'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}
