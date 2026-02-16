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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('title');
            $table->enum('product_type', ['paid', 'free', 'garage_sale']);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('condition_status')->nullable();
            $table->text('description')->nullable();

            // Main Image
            $table->string('product_image')->nullable();

            // Status
            $table->enum('status', ['active', 'expired', 'sold', 'archived'])->default('active');

            // Location
            $table->string('pickup_location')->nullable();
            $table->decimal('pickup_latitude', 10, 7)->nullable();
            $table->decimal('pickup_longitude', 10, 7)->nullable();
            $table->text('pickup_notes')->nullable();

            // Dates
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('sold_at')->nullable();

            // Spotlight
            $table->boolean('is_spotlighted')->default(false);
            $table->dateTime('spotlight_start_date')->nullable();
            $table->dateTime('spotlight_end_date')->nullable();
            $table->integer('boost_count')->default(0);
            $table->decimal('boost_fee', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
