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
        Schema::table('call_requests', function (Blueprint $table) {
            $table->decimal('commission_amount', 10, 2)->default(0.00)->after('call_cost');
            $table->decimal('astrologer_earnings', 10, 2)->default(0.00)->after('commission_amount');
        });

        Schema::table('chat_requests', function (Blueprint $table) {
            $table->integer('chat_duration')->default(0)->after('status')->nullable();
            $table->decimal('chat_cost', 10, 2)->default(0.00)->after('chat_duration');
            $table->decimal('commission_amount', 10, 2)->default(0.00)->after('chat_cost');
            $table->decimal('astrologer_earnings', 10, 2)->default(0.00)->after('commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_requests', function (Blueprint $table) {
            $table->dropColumn(['commission_amount', 'astrologer_earnings']);
        });

        Schema::table('chat_requests', function (Blueprint $table) {
            $table->dropColumn(['chat_duration', 'chat_cost', 'commission_amount', 'astrologer_earnings']);
        });
    }
};
