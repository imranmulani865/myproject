<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VideoProjectController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Video Projects & Shorts
    Route::get('/projects/create', [VideoProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [VideoProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [VideoProjectController::class, 'show'])->name('projects.show');
    Route::delete('/projects/{id}', [VideoProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/api/projects/{id}/status', [VideoProjectController::class, 'status'])->name('api.projects.status');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    // Token Wallet & Payments
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');
    Route::post('/payment/order', [\App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payment.order');
    Route::post('/payment/verify', [\App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payment.verify');

    // 90s Nostalgia Video Studio Showcase
    Route::get('/nostalgia-studio', function () {
        return view('nostalgia.studio');
    })->name('nostalgia.studio');
});

// Admin routes (Protected by auth and admin middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/status', [\App\Http\Controllers\AdminController::class, 'updateUserStatus'])->name('users.status');
    Route::post('/users/{user}/tokens', [\App\Http\Controllers\AdminController::class, 'adjustTokens'])->name('users.tokens');
    Route::get('/projects', [\App\Http\Controllers\AdminController::class, 'projects'])->name('projects');
    Route::get('/transactions', [\App\Http\Controllers\AdminController::class, 'transactions'])->name('transactions');
    Route::get('/payments', [\App\Http\Controllers\AdminController::class, 'payments'])->name('payments');
});
