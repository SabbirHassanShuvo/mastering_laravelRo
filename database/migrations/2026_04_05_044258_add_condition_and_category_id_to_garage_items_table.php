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
        Schema::table('garage_items', function (Blueprint $table) {
            $table->string('condition')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garage_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['condition', 'category_id']);
        });
    }
};
