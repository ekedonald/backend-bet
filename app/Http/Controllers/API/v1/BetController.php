<?php

namespace App\Http\Controllers\API\v1;

use App\Events\BetCreated;
use App\Exceptions\InsufficientBalanceException;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\Bet\CreateBetRequest;
use App\Http\Requests\Token\CreateTokenRequest;
use App\Models\Bet;
use App\Models\Pool;
use App\Models\Ticker;
use App\Models\Token;
use App\Services\AccountService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BetController extends Controller
{
    protected $betFilter = ['amount', 'bet_price', 'choice'];
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
            $search      = $request->get('search');

            $bets = Bet::
                with('user:id,first_name,avatar,last_name', 'pool', 'ticker', 'ticker.base_token', 'ticker.target_token')
                ->when($search, function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%');
                })
                ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->betFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Bet list gotten',
                'bets' => $bets,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function showUserBets(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0)  ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0);
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');

            $bets = Bet::with('user:id,first_name,avatar,last_name', 'pool', 'ticker', 'ticker.base_token', 'ticker.target_token')
                ->where('user_id', auth()->id())
                ->when($search, function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%');
                })
                ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->betFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Bet list gotten',
                'bets' => $bets,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getUserBets(PaginateRequest $request, string $userId)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0);
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');

            $bets = Token::
                where('user_id', $userId)
                ->when($search, function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%');
                })
                ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->betFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Bet list gotten',
                'bets' => $bets,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getTickerBets(PaginateRequest $request, string $tickerId)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0);
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');

            $bets = Bet::
                where('ticker_id', $tickerId)
                ->when($search, function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%');
                })
                ->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->betFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Bet list gotten',
                'bets' => $bets,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            report($exception);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getPoolBets(PaginateRequest $request, string $poolId)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0);
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';
            $search      = $request->get('search');

            $bets = Bet::
                when($search, function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%');
                })
                ->whereHas('ticker', function ($query) use ($poolId) {
                $query->where('pool_id', $poolId);
                    })->where(function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->betFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'User Bet list gotten',
                'bets' => $bets,
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
    public function store(CreateBetRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $pool = Pool::findOrFail($request->pool_id);
            $ticker = Ticker::findOrFail($pool->ticker_id);
            $checkBet = Bet::where('ticker_id', $ticker->id)
                ->where('pool_id', $pool->id)
                ->where('user_id', $user->id)
                ->first();
            if($checkBet){
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => 'Bet already placed, wait for bet result'
                ], Response::HTTP_BAD_GATEWAY);
            }

            $account = new AccountService($user);
            $account->placeBet($request->bet_price);

            $bet = new Bet();
            $bet->ticker_id = $ticker->id;
            $bet->pool_id = $pool->id;
            $bet->bet_price = $request->bet_price;
            $bet->choice = $request->choice;
            $bet->choice_outcome = $request->choice_outcome;
            $bet->user_id = auth()->id();
            $bet->save();
            
            DB::commit();
            try {
                event(new BetCreated($bet));
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Bet has been recorded',
                'bet' => $bet
            ], Response::HTTP_OK);
        } catch (InsufficientBalanceException $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return ResponseController::response(false,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_BAD_GATEWAY);
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'Bet cannot be created, try again'
            ], Response::HTTP_BAD_GATEWAY);
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
        $bet = Bet::where('id', $id)->first();
        if(!$bet){
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'bet not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return ResponseController::response(true,[
            'bet' => $bet
        ], Response::HTTP_OK);
    }

    public function showAUserBet($id)
    {
        $userId = auth()->id();
        $bet = Bet::
            with('ticker', 'pool', 'ticker.base_token', 'ticker.target_token')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();
        if(!$bet){
            return ResponseController::response(false,[
                ResponseController::MESSAGE => 'bet not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return ResponseController::response(true,[
            'bet' => $bet
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
            $ticker = Ticker::findOrFail($request->ticker_id);
            $bet = Bet::finOrFail($id);
            $bet->ticker_id = $ticker->id;
            $bet->bet_price = $request->bet_price;
            $bet->choice = $request->choice;
            $bet->user_id = auth()->id();
            $bet->save();

            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'Bet has been updated',
                'bet' => $bet
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
            $bet = Bet::findOrFail($id);
            $bet->delete();
            return ResponseController::response(true,[
                ResponseController::MESSAGE => 'bet has been deleted'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            report($e);
            return ResponseController::response(true,[
                ResponseController::MESSAGE => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}
