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
        Schema::create('astrologer_profile_specialization', function (Blueprint $table) {
            $table->foreignId('astrologer_profile_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->foreignId('specialization_id')->constrained('specializations')->onDelete('cascade');

            // Composite primary key
            $table->primary(['astrologer_profile_id', 'specialization_id'], 'ap_spec_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_profile_specialization');
    }
};
