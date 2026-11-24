<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_account_can_be_granted_admin_access(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $exitCode = Artisan::call('admin:grant', ['email' => $user->email]);

        $this->assertSame(0, $exitCode);
        $this->assertTrue($user->fresh()->isAdmin());
        $this->assertDatabaseCount('users', 1);
    }

    public function test_database_seeder_does_not_create_accounts_with_shared_passwords(): void
    {
        $this->artisan('db:seed')->assertExitCode(0);

        $this->assertDatabaseCount('users', 0);
    }
}
