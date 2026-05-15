<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

// Public
Route::get('/', function () {
    $categories = Category::all();
    $lastListings = Listing::where('is_active', true)->latest()->take(3)->get();
    return view('welcome', compact('categories', 'lastListings'));
});
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');

// Auth Group
Route::middleware(['auth', 'verified'])->group(function () {
    // Priorité aux annonces
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');

    Route::get('/dashboard', function () {
        $myListings = Listing::where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('myListings'));
    })->name('dashboard');

    // Messagerie
    Route::get('/messages', [AdminDashboardController::class, 'messages'])->name('admin.messages');
    Route::get('/messages/{message}', [AdminDashboardController::class, 'showMessage'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'store'])->name('messages.store');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route dynamique (toujours en dernier)
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/utilisateurs', [AdminDashboardController::class, 'users'])->name('admin.users.index');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::delete('/listings/{listing}', [AdminDashboardController::class, 'destroyListing'])->name('admin.listings.destroy');
});

require __DIR__.'/auth.php';