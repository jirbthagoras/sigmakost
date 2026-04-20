<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Debug route to test translations
Route::get('/test-locale', function () {
    return [
        'current_locale' => app()->getLocale(),
        'test_translation' => __('app.app_name'),
        'available_locales' => config('app.locale'),
    ];
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// User Kost Routes (public access)
Route::get('/kost', [UserController::class, 'index'])->name('kost.index');
Route::get('/kost/{kost}', [UserController::class, 'show'])->name('kost.show');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-bookings', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/kost/{kost}/book', [RentalController::class, 'store'])->name('kost.book');
    Route::post('/kost/{kost}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('kost.review');

    // Payment Routes
    Route::get('/my-payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Kost Management
    Route::resource('kosts', KostController::class);

    // Kost Status Management
    Route::patch('kosts/{kost}/status', [KostController::class, 'updateStatus'])
        ->name('kosts.status');

    // Image Management Routes
    Route::delete('kost-images/{image}', [KostController::class, 'deleteImage'])
        ->name('kost-images.destroy');
    Route::patch('kost-images/{image}/primary', [KostController::class, 'setPrimaryImage'])
        ->name('kost-images.primary');

    // Rental Requests Management
    Route::get('requests', [AdminRentalController::class, 'index'])->name('rentals.index');
    Route::patch('requests/{rental}/status', [AdminRentalController::class, 'updateStatus'])->name('rentals.status');

    // Payment Management
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
});
