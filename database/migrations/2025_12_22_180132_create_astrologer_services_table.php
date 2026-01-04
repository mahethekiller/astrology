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
        Schema::create('astrologer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->enum('service_type', ['chat', 'call', 'video']);
            $table->decimal('price_per_minute', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for filtering active services
            $table->index('astrologer_id');
            $table->index('service_type');
            $table->index('is_active');

            // Unique constraint to prevent duplicate service types per astrologer
            $table->unique(['astrologer_id', 'service_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_services');
    }
};
