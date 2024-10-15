<?php

namespace App\Jobs;

use App\Models\Bet;
use App\Models\Pool;
use App\Models\User;
use App\Services\AccountService;
use App\Services\AppConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettleBetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pools = Pool::where('closed_at', '<', now()->toIso8601String())->whereNull('settled_at')->limit(100)->get();
        $pools->each(function($pool) {
            // Fetch all bets for the pool
            $bets = Bet::where('ticker_id', $pool->ticker_id)->where('settled', false)->get();

            // Check if there are no bets in the pool
            if ($bets->isEmpty()) {
                $pool->settled_at = now();
                $pool->save();
                Log::info("No bets placed in the pool ID {$pool->id} for ticker ID {$pool->ticker_id}.");
                return;
            }

            // Calculate the total money in the pool
            $totalPoolMoney = $bets->sum('bet_price');

            // Deduct the app's fee (20% of the total pool money)
            $appFee = $totalPoolMoney * 0.20;

            // Calculate the remaining pool money (80% of the total pool money)
            $remainingPoolMoney = $totalPoolMoney - $appFee;

            // Filter winning bets
            $winningBets = $bets->filter(function ($bet) use ($pool) {
                switch ($bet->choice) {
                    case AppConfig::BET_BASE_DOWN:
                        return $pool->base_token_start_price > $pool->base_token_end_price;
                    case AppConfig::BET_BASE_UP:
                        return $pool->base_token_start_price < $pool->base_token_end_price;
                    case AppConfig::BET_TARGET_DOWN:
                        return $pool->target_token_start_price > $pool->target_token_end_price;
                    case AppConfig::BET_TARGET_UP:
                        return $pool->target_token_start_price < $pool->target_token_end_price;
                    default:
                        return false;
                }
            });

            // Check if everyone bets on the same result and nobody loses
            if ($winningBets->count() === $bets->count()) {
                Log::info("All bets are on the same result for ticker ID {$pool->ticker_id}, no losses.");
                DB::transaction(function () use ($bets, $pool) {
                    foreach ($bets as $bet) {
                        $user = User::findOrFail($bet->user_id);
                        if ($user) {
                            $account = new AccountService($user); // Refund bet amount
                            $account->creditBet($bet->bet_price, AppConfig::TRANSACTION_TYPE_PLACE_BET_DRAW);
                        }
                        $bet->settled = true;
                        $bet->status = AppConfig::BET_STATUS_DRAW;
                        $bet->save();
                    }
                    $pool->settled_at = now();
                    $pool->save();
                });
                return;
            }

            // Calculate total winning amount
            $totalWinningAmount = $winningBets->sum('bet_price');

            // Check if total winning amount is zero
            if ($totalWinningAmount === 0) {
                Log::info("Total winning amount is zero for ticker ID {$pool->ticker_id}, no payouts needed.");
                DB::transaction(function () use ($bets, $pool) {
                    foreach ($bets as $bet) {
                        $user = User::findOrFail($bet->user_id);
                        if ($user) {
                            $account = new AccountService($user); // Refund bet amount
                            $account->creditBet($bet->bet_price, AppConfig::TRANSACTION_TYPE_PLACE_BET_DRAW);
                        }
                        $bet->settled = true;
                        $bet->status = AppConfig::BET_STATUS_NO_RESULT;
                        $bet->save();
                    }
                    $pool->settled_at = now();
                    $pool->save();
                });
                return;
            }

            // Attempt to settle bets in a transaction
            try {
                DB::transaction(function () use ($bets, $winningBets, $remainingPoolMoney, $totalWinningAmount, $pool) {
                    // Distribute the remaining pool money to the winners
                    foreach ($winningBets as $bet) {
                        $user = User::findOrFail($bet->user_id);
                        if (!$user) {
                            Log::error("User with ID {$bet->user_id} not found for bet ID {$bet->id}");
                            continue;
                        }
                        $betPercentage = $bet->bet_price / $totalWinningAmount;
                        $payout = $remainingPoolMoney * $betPercentage;
                        
                        $account = new AccountService($user);
                        $account->creditBet($payout, AppConfig::TRANSACTION_TYPE_PLACE_BET_WON);
                        
                        $bet->settled = true;
                        $bet->status = AppConfig::BET_STATUS_WON;
                        $bet->save();
                    }

                    // Mark losing bets
                    $losingBets = $bets->diff($winningBets);
                    foreach ($losingBets as $bet) {
                        $bet->settled = true;
                        $bet->status = AppConfig::BET_STATUS_LOST;
                        $bet->save();
                    }

                    // Mark the pool as settled
                    $pool->settled_at = now();
                    $pool->save();
                });
            } catch (\Exception $e) {
                Log::error("Failed to settle bets for ticker ID {$pool->ticker_id}: {$e->getMessage()}");
            }
        });
    }
}
