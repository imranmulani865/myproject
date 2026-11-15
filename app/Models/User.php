<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'tokens',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tokens' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasTokens(int $cost): bool
    {
        return $this->tokens >= $cost;
    }

    public function deductTokens(int $amount, string $description, ?int $projectId = null): bool
    {
        if ($this->tokens < $amount) {
            return false;
        }

        $this->decrement('tokens', $amount);

        $this->tokenTransactions()->create([
            'amount' => -$amount,
            'type' => 'usage',
            'description' => $description,
            'project_id' => $projectId,
        ]);

        return true;
    }

    public function addTokens(int $amount, string $description, ?int $adminId = null, string $type = 'manual_credit'): void
    {
        $this->increment('tokens', $amount);

        $this->tokenTransactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'admin_id' => $adminId,
        ]);
    }

    public function videoProjects(): HasMany
    {
        return $this->hasMany(VideoProject::class)->latest();
    }

    public function generatedShorts(): HasMany
    {
        return $this->hasMany(GeneratedShort::class)->latest();
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class)->latest();
    }

    public function tokenTransactions(): HasMany
    {
        return $this->hasMany(TokenTransaction::class)->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->where('is_read', false)->count();
    }
}
