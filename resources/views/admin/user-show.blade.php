@extends('layouts.app')

@section('title', 'User account • Admin')

@section('content')
<div class="mx-auto max-w-7xl space-y-7 py-4">
    @include('admin.navigation')
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-[#4D5CFF] hover:underline">← All users</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-tight text-[#152238]">{{ $user->name }}</h1>
            <p class="mt-1 text-sm text-[#5E6C83]">{{ $user->email }} · Joined {{ $user->created_at?->format('M j, Y') }}</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-bold">
            <span class="rounded-full bg-[#E7EAFF] px-3 py-1.5 text-[#3E4BE8]">{{ ucfirst($user->role) }}</span>
            <span class="rounded-full px-3 py-1.5 {{ $user->status === 'active' ? 'bg-[#E5F6ED] text-[#257551]' : 'bg-[#FFF0E8] text-[#AD583B]' }}">{{ ucfirst($user->status) }}</span>
        </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Credit balance</p><p class="mt-2 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($user->tokens) }}</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Projects</p><p class="mt-2 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($user->video_projects_count) }}</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Generated shorts</p><p class="mt-2 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($user->generated_shorts_count) }}</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Notifications</p><p class="mt-2 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($user->app_notifications_count) }}</p></div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-status-heading">
            <h2 id="user-status-heading" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Account status</h2>
            <p class="mt-1 text-xs text-[#687684]">Suspended and pending accounts cannot access protected pages.</p>
            <form method="POST" action="{{ route('admin.users.status', $user) }}" class="mt-4 flex flex-wrap items-end gap-3">
                @csrf
                <label class="min-w-40 flex-1 text-xs font-semibold text-[#526078]">Status
                    <select name="status" class="mt-1 block w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-3 py-2.5 text-sm text-[#152238]">
                        @foreach (['active', 'pending', 'suspended'] as $accountStatus)
                            <option value="{{ $accountStatus }}" @selected($user->status === $accountStatus)>{{ ucfirst($accountStatus) }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="rounded-xl bg-[#4D5CFF] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#3E4BE8]">Save status</button>
            </form>
        </section>
        <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-credits-heading">
            <h2 id="user-credits-heading" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Adjust credits</h2>
            <p class="mt-1 text-xs text-[#687684]">Every adjustment is recorded with your account and reason.</p>
            <form method="POST" action="{{ route('admin.users.tokens', $user) }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
                @csrf
                <label class="text-xs font-semibold text-[#526078]">Action
                    <select name="action" class="mt-1 block w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-3 py-2.5 text-sm text-[#152238]"><option value="credit">Add</option><option value="debit">Deduct</option></select>
                </label>
                <label class="text-xs font-semibold text-[#526078]">Credits
                    <input name="amount" type="number" min="1" max="10000" required class="mt-1 block w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-3 py-2.5 text-sm text-[#152238]">
                </label>
                <button type="submit" class="rounded-xl bg-[#4D5CFF] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#3E4BE8]">Apply</button>
                <label class="text-xs font-semibold text-[#526078] sm:col-span-3">Audit reason
                    <input name="note" type="text" maxlength="255" required placeholder="Why is this balance changing?" class="mt-1 block w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-3 py-2.5 text-sm text-[#152238]">
                </label>
            </form>
        </section>
    </div>

    <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-projects-heading">
        <h2 id="user-projects-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Projects</h2>
        <div class="mt-4 overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="border-b border-[#E7ECF5] text-xs text-[#687684]"><tr><th class="pb-3 pr-4">Project</th><th class="pb-3 pr-4">Status</th><th class="pb-3 pr-4">Shorts</th><th class="pb-3">Created</th></tr></thead><tbody class="divide-y divide-[#EDF0F6]">
            @forelse ($projects as $project)
                <tr><td class="py-3 pr-4"><a href="{{ route('admin.projects.show', $project) }}" class="font-bold text-[#3E4BE8] hover:underline">{{ $project->title ?: 'Untitled project' }}</a><span class="block text-xs text-[#687684]">{{ ucfirst($project->mode) }} · {{ ucfirst($project->source_type) }}</span></td><td class="py-3 pr-4 capitalize text-[#43536D]">{{ $project->status }}</td><td class="py-3 pr-4 text-[#43536D]">{{ $project->shorts_count }}</td><td class="py-3 text-[#687684]">{{ $project->created_at?->format('M j, Y') }}</td></tr>
            @empty
                <tr><td colspan="4" class="py-6 text-[#687684]">No projects yet.</td></tr>
            @endforelse
        </tbody></table></div>
        @if ($projects->hasPages()) <div class="mt-4">{{ $projects->links() }}</div> @endif
    </section>

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-payments-heading">
            <h2 id="user-payments-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Payment orders</h2>
            <div class="mt-4 space-y-3">
                @forelse ($payments as $payment)
                    <div class="rounded-xl border border-[#EDF0F6] bg-[#FAFBFE] p-3 text-sm"><div class="flex justify-between gap-3"><strong class="text-[#152238]">{{ $payment->formatted_amount }} · {{ ucfirst($payment->plan_id) }}</strong><span class="font-semibold capitalize text-[#526078]">{{ $payment->status }}</span></div><p class="mt-1 break-all font-mono text-[11px] text-[#687684]">{{ $payment->razorpay_order_id }}</p><p class="mt-1 text-xs text-[#687684]">{{ $payment->created_at?->format('M j, Y g:i A') }} · {{ $payment->tokens }} credits</p>@if ($payment->error_reason)<p class="mt-2 text-xs text-[#A84339]">{{ $payment->error_reason }}</p>@endif</div>
                @empty <p class="text-sm text-[#687684]">No payment orders yet.</p>
                @endforelse
            </div>
            @if ($payments->hasPages()) <div class="mt-4">{{ $payments->links() }}</div> @endif
        </section>
        <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-ledger-heading">
            <h2 id="user-ledger-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Credit ledger</h2>
            <div class="mt-4 space-y-3">
                @forelse ($transactions as $transaction)
                    <div class="rounded-xl border border-[#EDF0F6] bg-[#FAFBFE] p-3 text-sm"><div class="flex justify-between gap-3"><strong class="capitalize text-[#152238]">{{ str_replace('_', ' ', $transaction->type) }}</strong><span class="font-bold {{ $transaction->amount >= 0 ? 'text-[#257551]' : 'text-[#A84339]' }}">{{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}</span></div><p class="mt-1 text-[#526078]">{{ $transaction->description }}</p><p class="mt-1 text-xs text-[#8792A6]">{{ $transaction->created_at?->format('M j, Y g:i A') }}@if ($transaction->admin_id) · Admin #{{ $transaction->admin_id }}@endif</p></div>
                @empty <p class="text-sm text-[#687684]">No credit transactions yet.</p>
                @endforelse
            </div>
            @if ($transactions->hasPages()) <div class="mt-4">{{ $transactions->links() }}</div> @endif
        </section>
    </div>

    <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="user-notifications-heading">
        <h2 id="user-notifications-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Notifications</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
            @forelse ($notifications as $notification)
                <div class="rounded-xl border border-[#EDF0F6] bg-[#FAFBFE] p-3"><div class="flex justify-between gap-3"><strong class="text-sm text-[#152238]">{{ $notification->title }}</strong><span class="text-xs text-[#687684]">{{ $notification->is_read ? 'Read' : 'Unread' }}</span></div><p class="mt-1 text-sm text-[#526078]">{{ $notification->message }}</p><p class="mt-2 text-xs text-[#8792A6]">{{ ucfirst($notification->type) }} · {{ $notification->created_at?->format('M j, Y g:i A') }}</p></div>
            @empty <p class="text-sm text-[#687684]">No notifications yet.</p>
            @endforelse
        </div>
        @if ($notifications->hasPages()) <div class="mt-4">{{ $notifications->links() }}</div> @endif
    </section>
</div>
@endsection
