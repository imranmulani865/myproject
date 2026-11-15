<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedShort extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_project_id',
        'user_id',
        'title',
        'description',
        'tags',
        'start_seconds',
        'end_seconds',
        'duration_seconds',
        'hook_summary',
        'video_path',
        'thumbnail_path',
        'file_size_bytes',
    ];

    protected $casts = [
        'start_seconds' => 'float',
        'end_seconds' => 'float',
        'duration_seconds' => 'integer',
        'file_size_bytes' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(VideoProject::class, 'video_project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getVideoUrlAttribute(): string
    {
        return asset($this->video_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset($this->thumbnail_path) : null;
    }

    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size_bytes) {
            return 'N/A';
        }
        $mb = round($this->file_size_bytes / 1048576, 2);
        return $mb . ' MB';
    }
}
