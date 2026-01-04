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
        Schema::create('astrologer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('astrologer_id')->constrained('astrologer_profiles')->onDelete('cascade');
            $table->enum('document_type', ['id_proof', 'certificate', 'experience_letter']);
            $table->string('document_path');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamps();

            // Indexes for filtering documents
            $table->index('astrologer_id');
            $table->index('document_type');
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_documents');
    }
};
