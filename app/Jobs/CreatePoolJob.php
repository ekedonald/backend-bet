<?php

namespace App\Jobs;

use App\Models\Pool;
use App\Models\Ticker;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreatePoolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $tickers = Ticker::get();
            $tickers->each(function($ticker) {
                $pool = new Pool();
                $pool->fetch_init_price_at = Carbon::now()->addMinutes(2)->setTimezone('UTC')->toAtomString();
                $pool->fetch_final_price_at = Carbon::now()->addMinutes(3)->setTimezone('UTC')->toAtomString();
                $pool->closed_at = Carbon::now()->addMinutes(5)->setTimezone('UTC')->toAtomString();
                $pool->ticker_id = $ticker->id;
                $pool->save();
            });
        } catch (\Exception $e) {
            Log::error("Failed to create pools: {$e->getMessage()}");
        }
    }
}
