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
        Schema::create('blood_request_posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('blood_type');
            $table->text('description');
            $table->string('location');
            $table->date('needed_by');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('open');
            $table->string('media_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_request_posts');
    }
};
