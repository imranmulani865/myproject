<nav class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1" aria-label="Admin sections">
    @foreach([
        ['route' => 'admin.dashboard', 'label' => 'Overview', 'icon' => 'fa-chart-pie'],
        ['route' => 'admin.users', 'label' => 'Users', 'icon' => 'fa-users'],
        ['route' => 'admin.projects', 'label' => 'Projects', 'icon' => 'fa-film'],
        ['route' => 'admin.transactions', 'label' => 'Credit ledger', 'icon' => 'fa-receipt'],
        ['route' => 'admin.payments', 'label' => 'Payments', 'icon' => 'fa-credit-card'],
    ] as $item)
        <a href="{{ route($item['route']) }}"
           @if(request()->routeIs($item['route'], $item['route'].'.*')) aria-current="page" @endif
           class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-bold transition-colors {{ request()->routeIs($item['route'], $item['route'].'.*') ? 'border-[#4D5CFF] bg-[#4D5CFF] text-white shadow-[0_8px_20px_rgba(77,92,255,.18)]' : 'border-[#DDE4F0] bg-white text-[#526078] hover:border-[#BBC5FF] hover:bg-[#F6F7FF] hover:text-[#3949E9]' }}">
            <i class="fa-solid {{ $item['icon'] }} text-xs" aria-hidden="true"></i>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
