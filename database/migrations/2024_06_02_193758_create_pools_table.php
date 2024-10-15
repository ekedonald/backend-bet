<?php

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
        Schema::create('pools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('target_token_start_price', 16, 8)->nullable();
            $table->decimal('target_token_end_price', 16, 8)->nullable();
            $table->decimal('base_token_start_price', 16, 8)->nullable();
            $table->decimal('base_token_end_price', 16, 8)->nullable();
            $table->string('closed_at')->nullable();
            $table->string('fetch_init_price_at')->nullable();
            $table->string('fetch_final_price_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->foreignUuid('ticker_id')
                ->references('id')
                ->on('tickers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pools');
    }
};
