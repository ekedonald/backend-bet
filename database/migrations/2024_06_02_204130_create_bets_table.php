<?php

use App\Services\AppConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('settled')->default(false);
            $table->string('choice_outcome')->nullable();
            $table->foreignUuid('ticker_id')
                ->references('id')
                ->on('tickers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignUuid('pool_id')
                ->references('id')
                ->on('pools')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->decimal('bet_price', 8, 2);
            $table->enum('choice', [
                AppConfig::BET_BASE_DOWN,
                AppConfig::BET_BASE_UP,
                AppConfig::BET_TARGET_DOWN,
                AppConfig::BET_TARGET_UP
            ]);
            $table->enum('status', [
                AppConfig::BET_STATUS_LOST,
                AppConfig::BET_STATUS_DRAW,
                AppConfig::BET_STATUS_WON,
                AppConfig::BET_STATUS_PENDING,
                AppConfig::BET_STATUS_NO_RESULT
            ])->default(AppConfig::BET_STATUS_PENDING);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
