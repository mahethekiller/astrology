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
        Schema::create('astrologer_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->enum('commission_type', ['percentage', 'fixed']);
            $table->decimal('commission_value', 5, 2);
            $table->date('effective_from');
            $table->timestamp('created_at')->useCurrent();

            // Indexes for querying commissions
            $table->index('astrologer_id');
            $table->index('effective_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_commissions');
    }
};
