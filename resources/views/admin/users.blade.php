@extends('layouts.app')

@section('title', 'Users and credits • Admin')

@section('content')
<div class="space-y-7" x-data="adminUsersManager()">
    <header class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-[#DDE2FF]/70 blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#4251CE] hover:underline">Admin overview</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Users and credits</h1>
            <p class="mt-2 text-sm text-[#687684]">Find an account, check its activity, and manage its credit balance.</p>
        </div>
    </header>

    @include('admin.navigation')

    <section class="rounded-2xl border border-[#DDE4F0] bg-white p-4 shadow-[0_10px_30px_rgba(21,34,56,.04)] sm:p-5" aria-label="Filter users">
        <form method="GET" action="{{ route('admin.users') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_190px_auto] md:items-end">
            <div>
                <label for="admin-user-search" class="mb-1.5 block text-xs font-bold text-[#526078]">Search users</label>
                <input id="admin-user-search" type="search" name="search" value="{{ request('search') }}" placeholder="Name or email"
                       class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
            </div>
            <div>
                <label for="admin-user-status" class="mb-1.5 block text-xs font-bold text-[#526078]">Status</label>
                <select id="admin-user-status" name="status" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-xl bg-[#4D5CFF] px-5 text-sm font-bold text-white hover:bg-[#3949E9] md:flex-none">Apply filters</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.users') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DDE4F0] bg-white px-4 text-sm font-bold text-[#526078] hover:bg-[#F6F7FF]">Reset</a>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="users-table-title">
        <div class="flex items-center justify-between border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
            <h2 id="users-table-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Accounts</h2>
            <span class="text-xs font-semibold text-[#687684]">{{ number_format($users->total()) }} total</span>
        </div>
        <div class="overflow-x-auto" role="region" aria-label="Users table" tabindex="0">
            <table class="min-w-[1020px] w-full text-left text-sm text-[#526078]">
                <thead class="bg-[#F8FAFF] text-xs font-bold text-[#687684]">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">User</th>
                        <th scope="col" class="px-5 py-3.5">Role</th>
                        <th scope="col" class="px-5 py-3.5">Status</th>
                        <th scope="col" class="px-5 py-3.5">Credits</th>
                        <th scope="col" class="px-5 py-3.5">Projects / shorts</th>
                        <th scope="col" class="px-5 py-3.5">Joined</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8ECF3]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#F8FAFF]">
                            <td class="px-5 py-4">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E8EAFF] font-bold text-[#4251CE]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="block max-w-[220px] truncate font-bold text-[#152238] hover:text-[#4251CE]">{{ $user->name }}@if($user->id === auth()->id()) <span class="font-medium text-[#8792A6]">(You)</span>@endif</a>
                                        <p class="max-w-[220px] truncate text-xs text-[#687684]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4"><span class="rounded-full border px-2.5 py-1 text-xs font-bold capitalize {{ $user->isAdmin() ? 'border-[#D8DFFF] bg-[#F1F3FF] text-[#4251CE]' : 'border-[#DDE4F0] bg-[#F8FAFF] text-[#526078]' }}">{{ $user->role }}</span></td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                    @csrf
                                    <label for="status-{{ $user->id }}" class="sr-only">Status for {{ $user->name }}</label>
                                    <select id="status-{{ $user->id }}" name="status" onchange="this.form.submit()"
                                            class="min-h-10 cursor-pointer rounded-xl border px-3 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-[#DDE2FF] {{ $user->status === 'active' ? 'border-emerald-200 bg-emerald-50 text-[#19734E]' : ($user->status === 'pending' ? 'border-amber-200 bg-amber-50 text-[#8D5D14]' : 'border-rose-200 bg-rose-50 text-[#B7473D]') }}">
                                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4 font-['Space_Grotesk'] font-bold text-[#152238]">{{ number_format($user->tokens) }}</td>
                            <td class="px-5 py-4 text-xs"><strong class="text-[#152238]">{{ $user->video_projects_count }}</strong> projects <span class="text-[#A0AABD]">/</span> <strong class="text-[#4251CE]">{{ $user->generated_shorts_count }}</strong> shorts</td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-[#687684]">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex min-h-10 items-center rounded-xl border border-[#DDE4F0] px-3 text-xs font-bold text-[#4251CE] hover:bg-[#F1F3FF]">View</a>
                                    <button type="button" data-name="{{ $user->name }}" data-action="{{ route('admin.users.tokens', $user->id) }}" @click="openAdjustModal({{ $user->id }}, $el.dataset.name, {{ $user->tokens }}, $el.dataset.action)"
                                            class="inline-flex min-h-10 items-center gap-1.5 rounded-xl bg-[#4D5CFF] px-3 text-xs font-bold text-white hover:bg-[#3949E9]">
                                        <i class="fa-solid fa-coins" aria-hidden="true"></i>Adjust credits
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-[#687684]">No users match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-[#E8ECF3] px-5 py-4">{{ $users->links() }}</div>
        @endif
    </section>

    <div x-cloak x-show="showModal" @keydown.escape.window="showModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-[#152238]/55 p-4 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div role="dialog" aria-modal="true" aria-labelledby="adjust-credits-title"
             class="relative w-full max-w-md rounded-2xl border border-[#DDE4F0] bg-white p-6 shadow-[0_24px_70px_rgba(21,34,56,.25)]" @click.outside="showModal = false">
            <button type="button" @click="showModal = false" aria-label="Close credit adjustment" class="absolute right-4 top-4 grid h-9 w-9 place-items-center rounded-lg text-[#687684] hover:bg-[#F1F3FF] hover:text-[#152238]"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            <div class="mb-5 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-[#FFF3E7] text-[#B66C1A]"><i class="fa-solid fa-coins" aria-hidden="true"></i></span>
                <div>
                    <h2 id="adjust-credits-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Adjust credits</h2>
                    <p class="text-xs text-[#687684]">Credit or debit a user's balance.</p>
                </div>
            </div>
            <div class="mb-5 flex items-center justify-between gap-3 rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] p-3">
                <div><span class="block text-xs text-[#687684]">User</span><strong class="text-sm text-[#152238]" x-text="targetUserName"></strong></div>
                <div class="text-right"><span class="block text-xs text-[#687684]">Current balance</span><strong class="text-sm text-[#4251CE]" x-text="targetUserTokens + ' credits'"></strong></div>
            </div>
            <form :action="targetAction" method="POST" class="space-y-4">
                @csrf
                <fieldset>
                    <legend class="mb-2 text-xs font-bold text-[#526078]">Operation</legend>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer"><input type="radio" name="action" value="credit" x-model="actionType" class="peer sr-only"><span class="block rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] p-3 text-center text-xs font-bold text-[#526078] peer-checked:border-[#77C7A4] peer-checked:bg-[#EDFAF2] peer-checked:text-[#19734E] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-[#4D5CFF]"><i class="fa-solid fa-plus mr-1" aria-hidden="true"></i>Credit (+)</span></label>
                        <label class="cursor-pointer"><input type="radio" name="action" value="debit" x-model="actionType" class="peer sr-only"><span class="block rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] p-3 text-center text-xs font-bold text-[#526078] peer-checked:border-[#F0AAA4] peer-checked:bg-[#FFF1EF] peer-checked:text-[#B7473D] peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-[#4D5CFF]"><i class="fa-solid fa-minus mr-1" aria-hidden="true"></i>Debit (-)</span></label>
                    </div>
                </fieldset>
                <div>
                    <label for="adjust-credit-amount" class="mb-1.5 block text-xs font-bold text-[#526078]">Number of credits</label>
                    <input id="adjust-credit-amount" type="number" name="amount" min="1" max="10000" required placeholder="e.g. 50"
                           class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm font-bold text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                </div>
                <div>
                    <label for="adjust-credit-note" class="mb-1.5 block text-xs font-bold text-[#526078]">Audit note / reason</label>
                    <input id="adjust-credit-note" type="text" name="note" required placeholder="Why is this balance changing?"
                           class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showModal = false" class="min-h-11 flex-1 rounded-xl border border-[#DDE4F0] bg-white px-4 text-sm font-bold text-[#526078] hover:bg-[#F6F7FF]">Cancel</button>
                    <button type="submit" class="min-h-11 flex-1 rounded-xl bg-[#4D5CFF] px-4 text-sm font-bold text-white hover:bg-[#3949E9]" x-text="actionType === 'credit' ? 'Add credits' : 'Deduct credits'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function adminUsersManager() {
    return {
        showModal: false,
        targetUserId: null,
        targetUserName: '',
        targetUserTokens: 0,
        targetAction: '',
        actionType: 'credit',
        openAdjustModal(id, name, tokens, action) {
            this.targetUserId = id;
            this.targetUserName = name;
            this.targetUserTokens = tokens;
            this.targetAction = action;
            this.actionType = 'credit';
            this.showModal = true;
        }
    };
}
</script>
@endpush
@endsection
