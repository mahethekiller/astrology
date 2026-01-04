<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->boolean('is_chat_online')->default(false);
            $table->boolean('is_call_online')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->dropColumn(['is_chat_online', 'is_call_online']);
        });
    }
};
