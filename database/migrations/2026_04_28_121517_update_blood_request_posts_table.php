<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blood_request_posts', function (Blueprint $table) {
            $table->dropColumn('blood_type');
            $table->foreignId('blood_type_id')->constrained('blood_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_request_posts', function (Blueprint $table) {

        });
    }
};
