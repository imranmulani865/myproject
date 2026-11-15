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
        Schema::create('video_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('source_type'); // 'youtube' or 'upload'
            $table->text('source_url')->nullable();
            $table->string('video_file_path')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('clip_duration_pref')->default(30); // 30 or 60
            $table->integer('clips_count')->default(3);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->string('progress_step')->default('Queued in background');
            $table->integer('progress_percent')->default(0);
            $table->longText('raw_analysis')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_projects');
    }
};
