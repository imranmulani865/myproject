<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:grant {email}', function (string $email): void {
    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        $this->fail('Register this email before granting admin access.');
    }

    if ($user->isAdmin()) {
        $this->info('This account already has admin access.');

        return;
    }

    $user->forceFill(['role' => 'admin'])->save();
    $this->info("Admin access granted to {$user->email}.");
})->purpose('Grant admin access to an existing registered account');
