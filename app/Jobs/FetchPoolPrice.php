<?php

namespace App\Jobs;

use App\Models\Pool;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchPoolPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pools = Pool::where('fetch_init_price_at', '<', now()->toIso8601String())
            ->whereNull('settled_at')
            ->limit(100)
            ->get();
        $pools->each(function($pool) {
            if(!$pool->target_token_start_price){
                $targetTokenData = Http::get('https://api.binance.com/api/v3/ticker/price', [
                    'symbol' => $pool->ticker->target_token->symbol,
                ]);
        
                if ($targetTokenData->successful()) {
                    $pool->target_token_start_price = $targetTokenData['price'];
                    $pool->save();
                }
            }

            if(!$pool->base_token_start_price){
                $baseTokenData = Http::get('https://api.binance.com/api/v3/ticker/price', [
                    'symbol' => $pool->ticker->base_token->symbol,
                ]);
        
                if ($baseTokenData->successful()) {
                    $pool->base_token_start_price = $baseTokenData['price'];
                    $pool->save();
                }
            }
        });

        $pools = Pool::where('fetch_final_price_at', '<', now()->toIso8601String())
            ->whereNull('settled_at')
            ->limit(100)
            ->get();
        $pools->each(function($pool) {
            $targetTokenData = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => $pool->ticker->target_token->symbol,
            ]);
    
            if ($targetTokenData->successful()) {
                $pool->target_token_end_price = $targetTokenData['price'];
                $pool->save();
            }

            $baseTokenData = Http::get('https://api.binance.com/api/v3/ticker/price', [
                'symbol' => $pool->ticker->base_token->symbol,
            ]);
    
            if ($baseTokenData->successful()) {
                $pool->base_token_end_price = $baseTokenData['price'];
                $pool->save();
            }
        });
    }
}
