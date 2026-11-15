<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'source_type',
        'source_url',
        'video_file_path',
        'duration_seconds',
        'clip_duration_pref',
        'clips_count',
        'status',
        'progress_step',
        'progress_percent',
        'raw_analysis',
        'error_message',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'clip_duration_pref' => 'integer',
        'clips_count' => 'integer',
        'progress_percent' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shorts(): HasMany
    {
        return $this->hasMany(GeneratedShort::class)->orderBy('id', 'asc');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
