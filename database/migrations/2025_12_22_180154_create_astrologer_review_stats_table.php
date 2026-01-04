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
        Schema::create('astrologer_review_stats', function (Blueprint $table) {
            $table->foreignId('astrologer_id')->primary()->constrained('astrologer_profiles')->onDelete('cascade');
            $table->decimal('avg_rating', 2, 1)->default(0.0);
            $table->integer('one_star')->default(0);
            $table->integer('two_star')->default(0);
            $table->integer('three_star')->default(0);
            $table->integer('four_star')->default(0);
            $table->integer('five_star')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Index for astrologer_id (already primary key, but explicit for clarity)
            $table->index('avg_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_review_stats');
    }
};
