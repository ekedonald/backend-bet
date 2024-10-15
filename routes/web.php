<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TickerController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('test', function() {
  return Carbon::createFromTimeString('2024-07-20T07:42:38.448554Z')->addMinutes(3);
});

Route::get('/', function() {
  return redirect("/login");
});

Route::group(['prefix' => 'login', 'middleware' => 'guest'], function() {
  Route::get('/', [AuthController::class, 'getLogin'])->name('login');
  Route::post('/', [AuthController::class, 'postLogin'])->name('postLogin');
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {

  Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');

  Route::group(['prefix' => 'auth'], function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  });

  Route::group(['prefix' => 'settings'], function() {

    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::post('/change-password', [AuthController::class, 'postChangePassword'])->name('post.password');

  });

  Route::group(['middleware' => ['role:admin']], function() {

    Route::group(['prefix' => 'permission'], function() {
      Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
      Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
      Route::get('/create', [PermissionController::class, 'create'])->name('permission.add');
      Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
      Route::get('/{id}', [PermissionController::class, 'show'])->name('permission.show');
      Route::put('/{id}', [PermissionController::class, 'update'])->name('permission.update');
      Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');
    });

    Route::group(['prefix' => 'role'], function() {
      Route::get('/', [RoleController::class, 'index'])->name('role.index');
      Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
      Route::get('/create', [RoleController::class, 'create'])->name('role.add');
      Route::post('/store', [RoleController::class, 'store'])->name('role.store');
      Route::get('/{id}', [RoleController::class, 'show'])->name('role.show');
      Route::put('/{id}', [RoleController::class, 'update'])->name('role.update');
      Route::delete('/{id}', [RoleController::class, 'destroy'])->name('role.delete');
    });
    
    Route::group(['prefix' => 'user'], function() {
      Route::get('/', [UserController::class, 'index'])->name('user.index');
      Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
      Route::get('/create', [UserController::class, 'create'])->name('user.add');
      Route::post('/store', [UserController::class, 'store'])->name('user.store');
      Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
      Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
      Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.delete');
    });

  });

  
  Route::group(['middleware' => ['role:admin|editor']], function() {
    
    Route::group(['prefix' => 'tickers'], function() {
      Route::get('/', [TickerController::class, 'index'])->name('tickers.index');
      Route::get('/edit/{id}', [TickerController::class, 'edit'])->name('tickers.edit');
      Route::get('/create', [TickerController::class, 'create'])->name('tickers.create');
      Route::post('/store', [TickerController::class, 'store'])->name('tickers.store');
      Route::get('/{id}', [TickerController::class, 'show'])->name('tickers.show');
      Route::put('/{id}', [TickerController::class, 'update'])->name('tickers.update');
      Route::delete('/{id}', [TickerController::class, 'destroy'])->name('tickers.delete');
    });

    Route::group(['prefix' => 'tokens'], function() {
      Route::get('/search', [TokenController::class, 'search'])->name('tokens.search');
      Route::get('/', [TokenController::class, 'index'])->name('tokens.index');
      Route::get('/edit/{id}', [TokenController::class, 'edit'])->name('tokens.edit');
      Route::get('/create', [TokenController::class, 'create'])->name('tokens.create');
      Route::post('/store', [TokenController::class, 'store'])->name('tokens.store');
      Route::get('/{id}', [TokenController::class, 'show'])->name('tokens.show');
      Route::put('/{id}', [TokenController::class, 'update'])->name('tokens.update');
      Route::delete('/{id}', [TokenController::class, 'destroy'])->name('tokens.delete');
    });

    Route::group(['prefix' => 'transactions'], function() {
      Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
      Route::get('{id}', [TransactionController::class, 'show'])->name('transactions.show');
    });

    Route::group(['prefix' => 'withdrawals'], function() {
      Route::get('/pending', [TransactionController::class, 'getWithdraws'])->name('withdrawals.pending');
      Route::post('/approve', [TransactionController::class, 'approve'])->name('withdrawals.approve');
    });
    
    Route::group(['prefix' => 'pools'], function() {
      Route::get('/', [PoolController::class, 'index'])->name('pools.index');
      Route::get('{id}', [PoolController::class, 'show'])->name('pools.show');
    });
  });

});


