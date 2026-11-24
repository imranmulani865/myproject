<?php

namespace Tests\Feature;

use App\Models\AppNotification;
use App\Models\GeneratedShort;
use App\Models\PaymentOrder;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Models\VideoProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_admins_can_open_user_and_project_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $member = User::factory()->create(['role' => 'user', 'status' => 'active']);
        $project = VideoProject::create([
            'user_id' => $member->id,
            'title' => 'Member podcast',
            'source_type' => 'upload',
            'status' => 'completed',
        ]);

        $this->get(route('admin.users.show', $member))->assertRedirect(route('login'));
        $this->actingAs($member)->get(route('admin.users.show', $member))->assertForbidden();
        $this->actingAs($member)->get(route('admin.projects.show', $project))->assertForbidden();

        $this->actingAs($admin)->get(route('admin.users.show', $member))
            ->assertOk()
            ->assertSeeText($member->email)
            ->assertSeeText('Member podcast');
        $this->actingAs($admin)->get(route('admin.projects.show', $project))
            ->assertOk()
            ->assertSeeText('Member podcast');
    }

    public function test_admin_details_show_related_records_without_payment_signature(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $member = User::factory()->create(['tokens' => 11]);
        $project = VideoProject::create([
            'user_id' => $member->id,
            'title' => 'Autumn story',
            'source_type' => 'youtube',
            'source_url' => 'https://www.youtube.com/watch?v=example',
            'status' => 'failed',
            'progress_percent' => 42,
            'progress_step' => 'Analyzing speech',
            'error_message' => 'Rendering stopped',
            'raw_analysis' => 'Spoken hook at 00:17',
        ]);
        GeneratedShort::create([
            'video_project_id' => $project->id,
            'user_id' => $member->id,
            'title' => 'Unexpected turn',
            'video_path' => 'media/shorts/p_'.$project->id.'/clip.mp4',
        ]);
        PaymentOrder::create([
            'user_id' => $member->id,
            'razorpay_order_id' => 'order_admin_test',
            'razorpay_signature' => 'private-signature-value',
            'plan_id' => 'trial',
            'amount_in_paise' => 9900,
            'tokens' => 3,
            'status' => 'paid',
        ]);
        TokenTransaction::create([
            'user_id' => $member->id,
            'amount' => 3,
            'type' => 'purchase',
            'description' => 'Trial credit purchase',
        ]);
        AppNotification::create([
            'user_id' => $member->id,
            'video_project_id' => $project->id,
            'title' => 'Project update',
            'message' => 'Review the result',
        ]);

        $this->actingAs($admin)->get(route('admin.users.show', $member))
            ->assertOk()
            ->assertSeeText('Autumn story')
            ->assertSeeText('Trial credit purchase')
            ->assertSeeText('Project update')
            ->assertSeeText('order_admin_test')
            ->assertDontSee('private-signature-value');

        $this->actingAs($admin)->get(route('admin.projects.show', $project))
            ->assertOk()
            ->assertSeeText('Unexpected turn')
            ->assertSeeText('Rendering stopped')
            ->assertSeeText('Spoken hook at 00:17');
    }

    public function test_admin_filters_projects_payments_and_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $member = User::factory()->create(['name' => 'Asha Creator']);
        $matchingProject = VideoProject::create([
            'user_id' => $member->id,
            'title' => 'Podcast highlight',
            'source_type' => 'upload',
            'mode' => 'dialogue',
            'status' => 'processing',
        ]);
        VideoProject::create([
            'user_id' => $member->id,
            'title' => 'Song intro',
            'source_type' => 'upload',
            'mode' => 'song',
            'status' => 'completed',
        ]);
        PaymentOrder::create([
            'user_id' => $member->id,
            'razorpay_order_id' => 'order_matching',
            'plan_id' => 'pro',
            'amount_in_paise' => 49900,
            'tokens' => 20,
            'status' => 'paid',
        ]);
        PaymentOrder::create([
            'user_id' => $member->id,
            'razorpay_order_id' => 'order_other',
            'plan_id' => 'trial',
            'amount_in_paise' => 9900,
            'tokens' => 3,
            'status' => 'created',
        ]);
        TokenTransaction::create(['user_id' => $member->id, 'amount' => 20, 'type' => 'purchase', 'description' => 'Pro pack']);
        TokenTransaction::create(['user_id' => $member->id, 'amount' => -1, 'type' => 'usage', 'description' => 'Video project']);

        $this->actingAs($admin)->get(route('admin.projects', ['search' => 'Podcast', 'status' => 'processing', 'mode' => 'dialogue']))
            ->assertOk()
            ->assertViewHas('projects', fn ($projects): bool => $projects->count() === 1 && $projects->first()->id === $matchingProject->id);
        $this->actingAs($admin)->get(route('admin.payments', ['status' => 'paid', 'plan' => 'pro']))
            ->assertOk()
            ->assertViewHas('payments', fn ($payments): bool => $payments->count() === 1 && $payments->first()->razorpay_order_id === 'order_matching');
        $this->actingAs($admin)->get(route('admin.transactions', ['type' => 'purchase']))
            ->assertOk()
            ->assertViewHas('transactions', fn ($transactions): bool => $transactions->count() === 1 && $transactions->first()->type === 'purchase');
        $this->actingAs($admin)->get(route('admin.projects', ['status' => 'unknown']))
            ->assertRedirect()
            ->assertSessionHasErrors('status');
    }

    public function test_admin_credit_adjustments_update_balance_and_ledger_together(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $member = User::factory()->create(['tokens' => 5]);
        $route = route('admin.users.tokens', $member);

        $this->actingAs($admin)->post($route, ['action' => 'credit', 'amount' => 7, 'note' => 'Correction'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $member->id, 'tokens' => 12]);
        $this->assertDatabaseHas('token_transactions', [
            'user_id' => $member->id,
            'admin_id' => $admin->id,
            'amount' => 7,
            'type' => 'manual_credit',
        ]);

        $this->actingAs($admin)->post($route, ['action' => 'debit', 'amount' => 4, 'note' => 'Correction'])
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $member->id, 'tokens' => 8]);
        $this->assertDatabaseHas('token_transactions', [
            'user_id' => $member->id,
            'admin_id' => $admin->id,
            'amount' => -4,
            'type' => 'manual_debit',
        ]);

        $this->actingAs($admin)->post($route, ['action' => 'debit', 'amount' => 20, 'note' => 'Too much'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $member->id, 'tokens' => 8]);
        $this->assertDatabaseCount('token_transactions', 2);
    }

    public function test_suspended_sessions_are_logged_out_and_logout_remains_available(): void
    {
        $member = User::factory()->create(['status' => 'active']);
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $this->actingAs($member);
        $member->update(['status' => 'suspended']);
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->actingAs($admin);
        $admin->update(['status' => 'pending']);
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->actingAs($member)->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_suspend_another_admin_but_cannot_suspend_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $otherAdmin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

        $this->actingAs($admin)->post(route('admin.users.status', $otherAdmin), ['status' => 'suspended'])
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id, 'status' => 'suspended']);

        $this->actingAs($admin)->post(route('admin.users.status', $admin), ['status' => 'suspended'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'status' => 'active']);
    }
}
