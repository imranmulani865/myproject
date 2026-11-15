@extends('layouts.app')

@section('title', 'Manage Users & Tokens')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="adminUsersManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Admin Panel</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-200">User Management</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                    <i class="fa-solid fa-users"></i>
                </span>
                User Management & Token Credit
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.transactions') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-receipt text-amber-400"></i>
                Token Ledger
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-950/80 border border-emerald-800/80 text-emerald-200 text-xs sm:text-sm flex items-center gap-3 shadow-lg">
            <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-950/80 border border-rose-800/80 text-rose-200 text-xs sm:text-sm flex items-center gap-3 shadow-lg">
            <i class="fa-solid fa-circle-xmark text-rose-400 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="mb-6 bg-slate-900/80 border border-slate-800 rounded-2xl p-4 shadow-lg">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or email address..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="w-full sm:w-48">
                <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-semibold transition">
                    Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.users') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs sm:text-sm transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/80 text-xs uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">User Details</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Token Balance</th>
                        <th class="px-6 py-4 font-semibold text-center">Projects / Shorts</th>
                        <th class="px-6 py-4 font-semibold">Joined Date</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/40 transition">
                            <!-- User Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white shadow">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white flex items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] font-semibold text-slate-400">(You)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-slate-800 text-slate-300 border border-slate-700' }}">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <!-- Status Switcher -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="inline-block">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-semibold px-2.5 py-1 rounded-lg border focus:outline-none transition cursor-pointer {{ $user->status === 'active' ? 'bg-emerald-950/60 text-emerald-300 border-emerald-800' : ($user->status === 'pending' ? 'bg-amber-950/60 text-amber-300 border-amber-800' : 'bg-rose-950/60 text-rose-300 border-rose-800') }}">
                                        <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Token Balance -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-black text-amber-300">
                                        {{ number_format($user->tokens) }}
                                    </span>
                                    <span class="text-xs text-amber-500/80">Tokens</span>
                                </div>
                            </td>

                            <!-- Projects & Shorts Count -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="font-bold text-white">{{ $user->video_projects_count }}</span>
                                <span class="text-xs text-slate-500">proj /</span>
                                <span class="font-bold text-indigo-400">{{ $user->generated_shorts_count }}</span>
                                <span class="text-xs text-slate-500">shorts</span>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button" @click="openAdjustModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->tokens }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-coins"></i>
                                    <span>Adjust Tokens</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-sm">
                                No users matched your search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- ADJUST TOKENS MODAL -->
    <div x-cloak x-show="showModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl relative" @click.outside="showModal = false">
            <button @click="showModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg border border-amber-500/30">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Manual Token Adjustment</h3>
                    <p class="text-xs text-slate-400">Credit or debit tokens for user</p>
                </div>
            </div>

            <!-- Target User Banner -->
            <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl mb-5 flex items-center justify-between">
                <div>
                    <span class="block text-[10px] uppercase font-semibold text-slate-500">User Account</span>
                    <span class="text-sm font-bold text-white" x-text="targetUserName"></span>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] uppercase font-semibold text-slate-500">Current Balance</span>
                    <span class="text-sm font-black text-amber-400" x-text="targetUserTokens + ' Tokens'"></span>
                </div>
            </div>

            <!-- Adjustment Form -->
            <form :action="'/admin/users/' + targetUserId + '/tokens'" method="POST" class="space-y-4">
                @csrf

                <!-- Action Type Switcher -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Operation Type
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="action" value="credit" x-model="actionType" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl bg-slate-950 border border-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-950/40 peer-checked:text-emerald-300 text-slate-300 font-bold text-xs transition">
                                <i class="fa-solid fa-plus mr-1"></i> Credit (+)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="action" value="debit" x-model="actionType" class="peer sr-only">
                            <div class="p-2.5 text-center rounded-xl bg-slate-950 border border-slate-700 peer-checked:border-rose-500 peer-checked:bg-rose-950/40 peer-checked:text-rose-300 text-slate-300 font-bold text-xs transition">
                                <i class="fa-solid fa-minus mr-1"></i> Debit (-)
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Number of Tokens <span class="text-rose-400">*</span>
                    </label>
                    <input type="number" name="amount" min="1" max="10000" required placeholder="e.g. 50, 100, 350"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 font-bold">
                </div>

                <!-- Reason / Audit Note -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">
                        Audit Note / Reason <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="note" required placeholder="e.g. UPI payment received ₹799 (UTR #12345)"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Submit / Cancel -->
                <div class="flex gap-3 pt-3">
                    <button type="button" @click="showModal = false" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition border border-slate-700">
                        Cancel
                    </button>
                    <button type="submit" 
                            :class="actionType === 'credit' ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-600/30'"
                            class="flex-1 py-2.5 rounded-xl text-xs font-bold transition shadow-lg flex items-center justify-center gap-2">
                        <i :class="actionType === 'credit' ? 'fa-solid fa-plus-circle' : 'fa-solid fa-minus-circle'"></i>
                        <span x-text="actionType === 'credit' ? 'Add Tokens' : 'Deduct Tokens'"></span>
                    </button>
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
        actionType: 'credit',

        openAdjustModal(id, name, tokens) {
            this.targetUserId = id;
            this.targetUserName = name;
            this.targetUserTokens = tokens;
            this.actionType = 'credit';
            this.showModal = true;
        }
    }
}
</script>
@endpush
@endsection
