<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\GeneratedShort;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Models\VideoProject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalTokens = User::sum('tokens');
        $totalProjects = VideoProject::count();
        $totalShorts = GeneratedShort::count();
        $pendingProjects = VideoProject::where('status', 'pending')->count();
        $processingProjects = VideoProject::where('status', 'processing')->count();
        $completedProjects = VideoProject::where('status', 'completed')->count();
        $failedProjects = VideoProject::where('status', 'failed')->count();
        $totalRevenuePaise = PaymentOrder::where('status', 'paid')->sum('amount_in_paise');

        $queueJobsCount = 0;
        $failedJobsCount = 0;
        $queueMetricsAvailable = true;
        try {
            $queueJobsCount = DB::table('jobs')->count();
            $failedJobsCount = DB::table('failed_jobs')->count();
        } catch (\Throwable) {
            $queueMetricsAvailable = false;
        }

        $recentUsers = User::latest()->take(5)->get();
        $recentTransactions = TokenTransaction::with(['user', 'admin'])->latest()->take(8)->get();
        $recentProjects = VideoProject::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalTokens',
            'totalProjects',
            'totalShorts',
            'pendingProjects',
            'processingProjects',
            'completedProjects',
            'failedProjects',
            'totalRevenuePaise',
            'queueJobsCount',
            'failedJobsCount',
            'queueMetricsAvailable',
            'recentUsers',
            'recentTransactions',
            'recentProjects'
        ));
    }

    public function users(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,pending,suspended'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;

        $query = User::withCount(['videoProjects', 'generatedShorts'])->latest();

        if ($search) {
            $query->where(function (Builder $userQuery) use ($search): void {
                $userQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', compact('users', 'search', 'status'));
    }

    public function showUser(User $user): View
    {
        $user->loadCount(['videoProjects', 'generatedShorts', 'appNotifications', 'paymentOrders']);

        $projects = $user->videoProjects()->withCount('shorts')
            ->paginate(8, ['*'], 'projects_page')->withQueryString();
        $payments = $user->paymentOrders()
            ->paginate(8, ['*'], 'payments_page')->withQueryString();
        $transactions = $user->tokenTransactions()
            ->paginate(10, ['*'], 'transactions_page')->withQueryString();
        $notifications = $user->appNotifications()
            ->paginate(8, ['*'], 'notifications_page')->withQueryString();

        return view('admin.user-show', compact('user', 'projects', 'payments', 'transactions', 'notifications'));
    }

    public function updateUserStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,pending,suspended',
        ]);

        if ($user->id === auth()->id() && $validated['status'] !== 'active') {
            return back()->with('error', 'You cannot suspend or change your own admin account status.');
        }

        $updated = DB::transaction(function () use ($user, $validated): bool {
            $activeAdminIds = User::query()
                ->where('role', 'admin')
                ->where('status', 'active')
                ->orderBy('id')
                ->lockForUpdate()
                ->pluck('id');
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->id);

            if ($lockedUser->isAdmin()
                && $lockedUser->isActive()
                && $validated['status'] !== 'active'
                && $activeAdminIds->count() <= 1
            ) {
                return false;
            }

            $lockedUser->update(['status' => $validated['status']]);

            return true;
        }, 3);

        if (! $updated) {
            return back()->with('error', 'At least one admin account must remain active.');
        }

        return back()->with('success', "Status for {$user->name} updated to {$validated['status']}.");
    }

    public function adjustTokens(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'integer', 'min:1', 'max:10000'],
            'note' => ['required', 'string', 'max:255'],
        ]);

        $amount = (int) $validated['amount'];
        $action = (string) $validated['action'];
        $description = 'Admin Adjustment: '.(string) $validated['note'];
        $adminId = (int) auth()->id();

        $balance = DB::transaction(function () use ($user, $amount, $action, $description, $adminId): ?int {
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->id);

            if ($action === 'debit' && $lockedUser->tokens < $amount) {
                return null;
            }

            if ($action === 'credit') {
                $lockedUser->increment('tokens', $amount);
            } else {
                $lockedUser->decrement('tokens', $amount);
            }

            $lockedUser->tokenTransactions()->create([
                'amount' => $action === 'credit' ? $amount : -$amount,
                'type' => $action === 'credit' ? 'manual_credit' : 'manual_debit',
                'description' => $description,
                'admin_id' => $adminId,
            ]);

            return (int) $lockedUser->fresh()->tokens;
        }, 3);

        if ($balance === null) {
            return back()->with('error', "Cannot debit {$amount} tokens from {$user->name}: insufficient balance.");
        }

        $verb = $action === 'credit' ? 'credited to' : 'debited from';

        return back()->with('success', "Successfully {$verb} {$user->name}: {$amount} tokens. New balance: {$balance} tokens.");
    }

    public function projects(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,processing,completed,failed'],
            'mode' => ['nullable', 'in:song,dialogue'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $mode = $filters['mode'] ?? null;

        $query = VideoProject::query()->with('user')->withCount('shorts')->latest();

        if ($search) {
            $query->where(function (Builder $projectQuery) use ($search): void {
                $projectQuery->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($mode) {
            $query->where('mode', $mode);
        }

        $projects = $query->paginate(15)->withQueryString();

        return view('admin.projects', compact('projects', 'search', 'status', 'mode'));
    }

    public function showProject(VideoProject $project): View
    {
        $project->load('user');

        $shorts = $project->shorts()
            ->paginate(12, ['*'], 'shorts_page')->withQueryString();
        $notifications = $project->notifications()->latest()
            ->paginate(8, ['*'], 'notifications_page')->withQueryString();

        return view('admin.project-show', compact('project', 'shorts', 'notifications'));
    }

    public function transactions(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:bonus,purchase,usage,manual_credit,manual_debit,refund'],
        ]);
        $search = $filters['search'] ?? null;
        $type = $filters['type'] ?? null;

        $query = TokenTransaction::query()->with(['user', 'admin', 'project'])->latest();

        if ($search) {
            $query->where(function (Builder $transactionQuery) use ($search): void {
                $transactionQuery->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.transactions', compact('transactions', 'search', 'type'));
    }

    public function payments(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:created,paid,failed'],
            'plan' => ['nullable', 'in:trial,starter,pro,agency,mega'],
        ]);
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $plan = $filters['plan'] ?? null;

        $query = PaymentOrder::query()->with('user')->latest();

        if ($search) {
            $query->where(function (Builder $paymentQuery) use ($search): void {
                $paymentQuery->where('razorpay_order_id', 'like', "%{$search}%")
                    ->orWhere('razorpay_payment_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($plan) {
            $query->where('plan_id', $plan);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments', compact('payments', 'search', 'status', 'plan'));
    }
}
