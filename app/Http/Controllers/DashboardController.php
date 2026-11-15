<?php

namespace App\Http\Controllers;

use App\Models\VideoProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $projects = VideoProject::where('user_id', $user->id)
            ->withCount('shorts')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_projects' => VideoProject::where('user_id', $user->id)->count(),
            'total_shorts' => $user->generatedShorts()->count(),
            'processing' => VideoProject::where('user_id', $user->id)->whereIn('status', ['pending', 'processing'])->count(),
            'completed' => VideoProject::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $recentNotifications = $user->appNotifications()->take(5)->get();

        return view('dashboard.index', compact('projects', 'stats', 'recentNotifications'));
    }

    public function wallet()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $transactions = $user->tokenTransactions()->latest()->paginate(15);

        return view('dashboard.wallet', compact('user', 'transactions'));
    }
}
