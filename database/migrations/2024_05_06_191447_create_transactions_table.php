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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type')
                ->comment(AppConfig::TRANSACTION_TYPE_DEBIT.","
                    .AppConfig::TRANSACTION_TYPE_CREDIT
                );
            $table->longText('payment_object')->nullable();
            $table->string('status')
                ->default(AppConfig::TRANSACTION_STATUS_WAITING)
                ->comment(AppConfig::TRANSACTION_STATUS_WAITING.","
                    .AppConfig::TRANSACTION_STATUS_CANCELLED
                );
            $table->string('reference_no')->nullable();
            $table->decimal('amount', 8,2);
            $table->string('sending_currency')
                ->default(AppConfig::DEFAULT_CURRENCY);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
