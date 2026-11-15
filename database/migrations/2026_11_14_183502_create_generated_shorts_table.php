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
        Schema::create('generated_shorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('tags')->nullable();
            $table->float('start_seconds')->default(0);
            $table->float('end_seconds')->default(0);
            $table->integer('duration_seconds')->default(30);
            $table->text('hook_reason')->nullable();
            $table->string('video_path');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_shorts');
    }
};
