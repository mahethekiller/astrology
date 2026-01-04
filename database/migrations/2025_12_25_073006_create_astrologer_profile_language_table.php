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
        Schema::create('astrologer_profile_language', function (Blueprint $table) {
            $table->foreignId('astrologer_profile_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            // Composite primary key
            $table->primary(['astrologer_profile_id', 'language_id'], 'ap_lang_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_profile_language');
    }
};
