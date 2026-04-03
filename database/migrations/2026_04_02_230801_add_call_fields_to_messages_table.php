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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('type')->default('message')->after('sender_id')->index();
            $table->string('call_type')->nullable()->after('type'); // audio, video
            $table->string('call_status')->nullable()->after('call_type')->index(); // completed, missed, declined, cancelled
            $table->integer('call_duration')->nullable()->after('call_status'); // in seconds
            $table->unsignedBigInteger('receiver_id')->nullable()->after('call_duration');

            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['receiver_id']);
            $table->dropColumn(['type', 'call_type', 'call_status', 'call_duration', 'receiver_id']);
        });
    }
};
