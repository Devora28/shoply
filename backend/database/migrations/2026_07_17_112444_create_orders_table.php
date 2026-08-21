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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('order_number', 32)->unique();
            $table->decimal('subtotal',15,2);
            $table->decimal('discount_amount',15,2)->default(0);
            $table->decimal('shipping_cost',15,2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total',15,2);
            $table->char('currency', 3)->default('USD');
            $table->enum('status',[
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->string('shipping_receiver_name');
            $table->string('shipping_company')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_country');
            $table->string('shipping_state');
            $table->string('shipping_city');
            $table->string('shipping_address');
            $table->string('shipping_postal_code')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index('status');
            $table->index('tracking_number');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
