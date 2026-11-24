@extends('layouts.app')

@section('title', 'Payments • Admin')

@section('content')
<div class="space-y-7">
    <header class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-[#DDE2FF]/70 blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#4251CE] hover:underline">Admin overview</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Payments</h1>
            <p class="mt-2 text-sm text-[#687684]">Review Razorpay orders, plan purchases, and issued credits.</p>
        </div>
    </header>

    @include('admin.navigation')

    <section class="rounded-2xl border border-[#DDE4F0] bg-white p-4 shadow-[0_10px_30px_rgba(21,34,56,.04)] sm:p-5" aria-label="Filter payments">
        <form method="GET" action="{{ route('admin.payments') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_155px_155px_auto] md:items-end">
            <div>
                <label for="admin-payment-search" class="mb-1.5 block text-xs font-bold text-[#526078]">Search payments</label>
                <input id="admin-payment-search" type="search" name="search" value="{{ request('search') }}" placeholder="User, email, order, or payment ID"
                       class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
            </div>
            <div>
                <label for="admin-payment-status" class="mb-1.5 block text-xs font-bold text-[#526078]">Status</label>
                <select id="admin-payment-status" name="status" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All statuses</option>
                    @foreach(['created' => 'Created', 'paid' => 'Paid', 'failed' => 'Failed'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="admin-payment-plan" class="mb-1.5 block text-xs font-bold text-[#526078]">Plan</label>
                <select id="admin-payment-plan" name="plan" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All plans</option>
                    @foreach(['trial', 'starter', 'pro', 'agency', 'mega'] as $plan)
                        <option value="{{ $plan }}" @selected(request('plan') === $plan)>{{ ucfirst($plan) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-xl bg-[#4D5CFF] px-5 text-sm font-bold text-white hover:bg-[#3949E9] md:flex-none">Apply filters</button>
                @if(request('search') || request('status') || request('plan'))
                    <a href="{{ route('admin.payments') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DDE4F0] bg-white px-4 text-sm font-bold text-[#526078] hover:bg-[#F6F7FF]">Reset</a>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="payments-table-title">
        <div class="flex items-center justify-between border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
            <h2 id="payments-table-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Payment orders</h2>
            <span class="text-xs font-semibold text-[#687684]">{{ number_format($payments->total()) }} results</span>
        </div>
        <div class="overflow-x-auto" role="region" aria-label="Payments table" tabindex="0">
            <table class="min-w-[1000px] w-full text-left text-sm text-[#526078]">
                <thead class="bg-[#F8FAFF] text-xs font-bold text-[#687684]">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Date and time</th>
                        <th scope="col" class="px-5 py-3.5">User</th>
                        <th scope="col" class="px-5 py-3.5">Plan</th>
                        <th scope="col" class="px-5 py-3.5">Amount</th>
                        <th scope="col" class="px-5 py-3.5">Credits</th>
                        <th scope="col" class="px-5 py-3.5">Status</th>
                        <th scope="col" class="px-5 py-3.5">Order / payment ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8ECF3]">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-[#F8FAFF]">
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-[#687684]">{{ $payment->created_at->format('M d, Y · h:i:s A') }}</td>
                            <td class="px-5 py-4">
                                @if($payment->user)
                                    <a href="{{ route('admin.users.show', $payment->user->id) }}" class="block max-w-[180px] truncate font-semibold text-[#152238] hover:text-[#4251CE]">{{ $payment->user->name }}</a>
                                    <span class="block max-w-[180px] truncate text-xs text-[#8792A6]">{{ $payment->user->email }}</span>
                                @else
                                    <span class="text-[#8792A6]">Deleted user</span>
                                @endif
                            </td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full border border-[#D8DFFF] bg-[#F1F3FF] px-2.5 py-1 text-xs font-bold capitalize text-[#4251CE]">{{ $payment->plan_id }}</span></td>
                            <td class="whitespace-nowrap px-5 py-4 font-['Space_Grotesk'] font-bold text-[#152238]">{{ $payment->formatted_amount }}</td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-[#152238]">+{{ number_format($payment->tokens) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-bold {{ $payment->status === 'paid' ? 'border-emerald-200 bg-emerald-50 text-[#19734E]' : ($payment->status === 'created' ? 'border-amber-200 bg-amber-50 text-[#8D5D14]' : 'border-rose-200 bg-rose-50 text-[#B7473D]') }}">
                                    {{ $payment->status === 'created' ? 'Pending' : ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono text-[11px] text-[#687684]">
                                <div><span class="font-semibold text-[#8792A6]">Order</span> {{ $payment->razorpay_order_id }}</div>
                                @if($payment->razorpay_payment_id)
                                    <div class="mt-1"><span class="font-semibold text-[#8792A6]">Paid</span> {{ $payment->razorpay_payment_id }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-[#687684]">No payments match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="border-t border-[#E8ECF3] px-5 py-4">{{ $payments->links() }}</div>
        @endif
    </section>
</div>
@endsection
