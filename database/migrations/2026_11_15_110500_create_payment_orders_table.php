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
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('razorpay_order_id')->unique()->index();
            $table->string('razorpay_payment_id')->nullable()->index();
            $table->string('razorpay_signature')->nullable();
            $table->string('plan_id'); // 'starter', 'pro', 'agency', 'custom'
            $table->integer('amount_in_paise'); // 29900 = Rs 299.00
            $table->string('currency')->default('INR');
            $table->integer('tokens'); // 100, 350, 1000
            $table->string('status')->default('created'); // 'created', 'paid', 'failed'
            $table->text('error_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
