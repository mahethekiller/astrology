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
        Schema::create('astrologer_expertises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->string('expertise', 100);
            $table->timestamp('created_at')->useCurrent();

            // Indexes for filtering and searching
            $table->index('astrologer_id');
            $table->index('expertise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_expertises');
    }
};
