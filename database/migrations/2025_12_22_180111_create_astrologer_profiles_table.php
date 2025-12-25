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
        Schema::create('astrologer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('display_name', 150);
            $table->string('slug', 160)->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->integer('experience_years');
            $table->longText('about');
            $table->string('profile_image');
            $table->string('cover_image')->nullable();
            $table->json('languages');
            $table->json('specializations');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_online')->default(false);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_consultations')->default(0);
            $table->timestamps();

            // Indexes for better query performance
            $table->index('slug');
            $table->index('verification_status');
            $table->index('is_featured');
            $table->index('is_online');
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('astrologer_profiles');
    }
};
