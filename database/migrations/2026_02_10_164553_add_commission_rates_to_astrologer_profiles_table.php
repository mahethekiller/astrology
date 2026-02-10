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
            $table->decimal('chat_commission_percentage', 5, 2)->nullable()->after('chat_price');
            $table->decimal('call_commission_percentage', 5, 2)->nullable()->after('call_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->dropColumn(['chat_commission_percentage', 'call_commission_percentage']);
        });
    }
};
