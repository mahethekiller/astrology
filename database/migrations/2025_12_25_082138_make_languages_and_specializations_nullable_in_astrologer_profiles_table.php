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
            $table->json('languages')->nullable()->change();
            $table->json('specializations')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->json('languages')->nullable(false)->change();
            $table->json('specializations')->nullable(false)->change();
        });
    }
};
