<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Restaurant\DashboardController;
use App\Http\Controllers\Restaurant\CategoryController;
use App\Http\Controllers\Restaurant\MenuItemController;
use App\Http\Controllers\Restaurant\QrCodeController;
use App\Http\Controllers\Restaurant\AnalyticsController;
use App\Http\Controllers\Restaurant\SettingsController;
use App\Http\Controllers\Restaurant\AccountController;
use App\Http\Controllers\Restaurant\StoryController;
use App\Http\Controllers\Restaurant\ArModelController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Account (accessible by all authenticated users)
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password');
});

// Restaurant Owner Routes (requires restaurant + active subscription)
Route::middleware(['auth', 'restaurant', 'subscribed'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Menu Items
    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::post('/menu-items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

    // Stories
    Route::resource('stories', StoryController::class)->except(['show']);
    Route::post('/stories/reorder', [StoryController::class, 'reorder'])->name('stories.reorder');

    // QR Codes
    Route::resource('qr-codes', QrCodeController::class)->except(['show']);
    Route::get('/qr-codes/{qrCode}/download/{format}', [QrCodeController::class, 'download'])->name('qr-codes.download');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    // AR Models
    Route::post('/menu-items/{menuItem}/ar/generate', [ArModelController::class, 'generate'])->name('ar.generate');
    Route::get('/menu-items/{menuItem}/ar/status', [ArModelController::class, 'status'])->name('ar.status');
    Route::delete('/menu-items/{menuItem}/ar', [ArModelController::class, 'destroy'])->name('ar.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::match(['put', 'post'], '/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('restaurants', AdminRestaurantController::class)->only(['index', 'show', 'destroy']);
    Route::resource('plans', AdminPlanController::class);
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
});

// Subscription & Plans
Route::middleware('auth')->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
    Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription', [SubscriptionController::class, 'manage'])->name('subscription.manage');
});
Route::post('/webhooks/abacatepay', [SubscriptionController::class, 'webhook'])->name('webhooks.abacatepay');

// Public Menu (must be last — catches slug)
Route::get('/{slug}', [MenuController::class, 'show'])->name('menu.public');
Route::post('/{slug}/track', [MenuController::class, 'track'])->name('menu.track');
Route::post('/{slug}/like', [MenuController::class, 'like'])->name('menu.like');
