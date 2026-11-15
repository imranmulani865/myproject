<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'tokens' => 9999,
            ]
        );

        // Creator User
        $creator = User::updateOrCreate(
            ['email' => 'creator@shortstudio.com'],
            [
                'name' => 'Pro Creator',
                'password' => Hash::make('Shorts@1234'),
                'role' => 'user',
                'status' => 'active',
                'tokens' => 150,
            ]
        );
    }
}
