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
        Schema::create('astrologer_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('0 = Sunday, 1 = Monday, ..., 6 = Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Indexes for querying availability
            $table->index('astrologer_id');
            $table->index('day_of_week');
            $table->index('is_available');

            // Unique constraint to prevent duplicate time slots for same day
            // Using custom name to avoid MySQL's 64 character limit
            $table->unique(['astrologer_id', 'day_of_week', 'start_time', 'end_time'], 'astrologer_availability_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_availabilities');
    }
};
