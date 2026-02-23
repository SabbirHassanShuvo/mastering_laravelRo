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
        Schema::create('garage_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('event_title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('pickup_location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('sale_start_date');
            $table->dateTime('sale_end_date');
            $table->decimal('posting_fee', 8,2)->default(2.99);
            $table->decimal('total_fee', 8,2)->default(2.99);
            $table->boolean('is_spotlighted')->default(false);
            $table->enum('status', ['active', 'expired', 'sold', 'archived'])->default('active');
            $table->boolean('notified_before_delete')->default(false);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_sales');
    }
};
