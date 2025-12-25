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
            $table->renameColumn('languages', 'languages_json');
            $table->renameColumn('specializations', 'specializations_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('astrologer_profiles', function (Blueprint $table) {
            $table->renameColumn('languages_json', 'languages');
            $table->renameColumn('specializations_json', 'specializations');
        });
    }
};
