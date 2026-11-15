<?php

namespace App\Http\Controllers;

use App\Models\GeneratedShort;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Models\VideoProject;
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

        $queueJobsCount = 0;
        try {
            $queueJobsCount = DB::table('jobs')->count();
        } catch (\Throwable $e) {
            $queueJobsCount = 0;
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
            'queueJobsCount',
            'recentUsers',
            'recentTransactions',
            'recentProjects'
        ));
    }

    public function users(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = User::withCount(['videoProjects', 'generatedShorts'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['active', 'pending', 'suspended'])) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', compact('users', 'search', 'status'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,pending,suspended',
        ]);

        if ($user->id === auth()->id() && $validated['status'] !== 'active') {
            return back()->with('error', 'You cannot suspend or change your own admin account status.');
        }

        $user->update(['status' => $validated['status']]);

        return back()->with('success', "Status for {$user->name} updated to {$validated['status']}.");
    }

    public function adjustTokens(Request $request, User $user)
    {
        $validated = $request->validate([
            'action' => 'required|in:credit,debit',
            'amount' => 'required|integer|min:1|max:10000',
            'note' => 'required|string|max:255',
        ]);

        $amount = (int) $validated['amount'];
        $note = $validated['note'];
        $action = $validated['action'];

        if ($action === 'credit') {
            $user->addTokens($amount, "Admin Adjustment: {$note}", auth()->id(), 'manual_credit');
            return back()->with('success', "Successfully credited {$amount} tokens to {$user->name}. New balance: {$user->tokens} tokens.");
        } else {
            if ($user->tokens < $amount) {
                return back()->with('error', "Cannot debit {$amount} tokens. User only has {$user->tokens} tokens.");
            }

            $user->decrement('tokens', $amount);
            $user->tokenTransactions()->create([
                'amount' => -$amount,
                'type' => 'manual_debit',
                'description' => "Admin Adjustment: {$note}",
                'admin_id' => auth()->id(),
            ]);

            return back()->with('success', "Successfully debited {$amount} tokens from {$user->name}. New balance: {$user->tokens} tokens.");
        }
    }

    public function projects(): View
    {
        $projects = VideoProject::with(['user', 'shorts'])->latest()->paginate(15);
        return view('admin.projects', compact('projects'));
    }

    public function transactions(): View
    {
        $transactions = TokenTransaction::with(['user', 'admin', 'project'])->latest()->paginate(20);
        return view('admin.transactions', compact('transactions'));
    }
}
