<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KostController;

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

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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
});
