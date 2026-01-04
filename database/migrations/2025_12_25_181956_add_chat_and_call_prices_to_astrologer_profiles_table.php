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
            $table->decimal('chat_price', 8, 2)->nullable()->after('status')->default(0);
            $table->decimal('call_price', 8, 2)->nullable()->after('chat_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->dropColumn(['chat_price', 'call_price']);
        });
    }
};
