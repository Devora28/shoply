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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('payment_number', 40)->unique();
            $table->string('provider', 50);
            $table->string('method', 30);
            $table->string('transaction_id')->nullable()->index();
            $table->string('provider_payment_id')->nullable()->index();
            $table->string('provider_customer_id')->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3);
            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'failed',
                'cancelled',
                'refunded',
                'partially_refunded',
            ])->default('pending');
            $table->string('card_brand', 30)->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->unsignedTinyInteger('card_expiry_month')->nullable();
            $table->unsignedSmallInteger('card_expiry_year')->nullable();
            $table->string('failure_code')->nullable();
            $table->text('failure_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
