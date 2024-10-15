<?php

use App\Http\Controllers\API\v1\{
    AuthController,
    BetController,
    PoolController,
    TickerController,
    TokenController,
    UserController,
};
use App\Http\Controllers\API\v1\Admin\{
    TransactionController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [AuthController::class, 'store']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('confirm-otp', [AuthController::class, 'confirmOTP']);
        Route::post('reset-password-with-otp', [AuthController::class, 'resetPasswordWithOtp']);
        Route::post('verify_otp', [AuthController::class, 'verifyOTP']);
        Route::post('refresh', [AuthController::class, 'generateAccessTokenFromRefreshToken']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {
        
        Route::group(['prefix' => 'verification'], function() {

            Route::post('verify_otp', [AuthController::class, 'verifyOTP']);
            Route::post('resend_otp', [AuthController::class, 'resend_otp']);

        });

        Route::group(['prefix' => 'auth'], function() {

            Route::get('user', [AuthController::class, 'getAuthUser']);
            Route::get('balance', [AuthController::class, 'getAuthUserBalance']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('change-password', [AuthController::class, 'resetPassword']);
            Route::post('add-profile-photo', [AuthController::class, 'addProfilePhoto']);

        });

        Route::group(['prefix' => 'user'], function() {
            
            Route::group(['prefix' => 'transaction'], function() {

                Route::post('status', [UserController::class, 'getStatus']);
                Route::post('deposit', [UserController::class, 'deposit']);
                Route::post('withdraw', [UserController::class, 'withdraw']);
                Route::get('/', [TransactionController::class, 'getUserTransactions']);
                Route::get('/{id}', [TransactionController::class, 'getUserTransaction']);

            });

            Route::group(['prefix' => 'ticker'], function() {

                Route::get('/', [TickerController::class, 'index']);
                Route::get('/{id}', [TickerController::class, 'show']);

            });

            Route::group(['prefix' => 'token'], function() {

                Route::get('/', [TokenController::class, 'index']);
                Route::get('/{id}', [TokenController::class, 'show']);

            });

            Route::group(['prefix' => 'pool'], function() {

                Route::get('/', [PoolController::class, 'index']);
                Route::get('/{id}', [PoolController::class, 'show']);

            });

            Route::group(['prefix' => 'bet'], function() {

                Route::get('/', [BetController::class, 'showUserBets']);
                Route::get('/{id}', [BetController::class, 'showAUserBet']);
                Route::post('/', [BetController::class, 'store']);

            });

        });

        Route::group(['prefix' => 'admin'], function() {
            
            Route::group(['prefix' => 'transaction'], function() {

                Route::get('/', [TransactionController::class, 'index']);
                Route::get('/show', [TransactionController::class, 'show']);

            });

            Route::group(['prefix' => 'ticker'], function() {

                Route::post('/', [TickerController::class, 'store']);
                Route::put('/{id}', [TickerController::class, 'update']);
                Route::delete('/{id}', [TickerController::class, 'destroy']);

            });

            Route::group(['prefix' => 'token'], function() {

                Route::post('/', [TokenController::class, 'store']);
                Route::put('/{id}', [TokenController::class, 'update']);
                Route::delete('/{id}', [TokenController::class, 'destroy']);

            });

            Route::get('/users/{userId}/bets', [BetController::class, 'getUserBets']);
            
            Route::get('/tickers/{tickerId}/bets', [BetController::class, 'getTickerBets']);
            
            Route::get('/pools/{poolId}/bets', [BetController::class, 'getPoolBets']);

        });

    });

});
