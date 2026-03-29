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
        Schema::create('spotlight_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->unique();
            $table->string('stripe_payment_method_id')->nullable();
            $table->decimal('posting_fee', 8, 2)->default(0);
            $table->decimal('total_fee', 8, 2)->default(0);
            $table->string('currency', 3)->default('usd');
            $table->string('boost_plan');
            $table->integer('boost_hours')->default(48);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('spotlight_start_at')->nullable();
            $table->timestamp('spotlight_end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spotlight_payments');
    }
};
