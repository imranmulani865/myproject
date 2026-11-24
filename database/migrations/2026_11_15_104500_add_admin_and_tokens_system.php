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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // 'user', 'admin'
            $table->string('status')->default('active')->after('role'); // 'active', 'pending', 'suspended'
            $table->integer('tokens')->default(20)->after('status'); // initial 20 free trial tokens
        });

        Schema::table('video_projects', function (Blueprint $table) {
            $table->string('mode')->default('dialogue')->after('clip_duration_pref'); // 'dialogue', 'song'
            $table->integer('tokens_used')->default(0)->after('mode');
        });

        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount'); // +50 or -15
            $table->string('type'); // 'bonus', 'purchase', 'usage', 'manual_credit', 'manual_debit', 'refund'
            $table->text('description')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('video_projects')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_transactions');

        Schema::table('video_projects', function (Blueprint $table) {
            $table->dropColumn(['mode', 'tokens_used']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'tokens']);
        });
    }
};
