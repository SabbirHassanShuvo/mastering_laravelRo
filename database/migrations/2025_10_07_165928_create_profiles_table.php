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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Admin 
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->string('address')->nullable();

            $table->string('user_name')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('bio')->nullable();
            $table->string('phone')->nullable();
            $table->enum('user_type', ['standard', 'verified', 'premium'])->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('search_radius_km')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
