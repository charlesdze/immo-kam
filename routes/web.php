<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ==========================================
// ROUTES PUBLIQUES
// ==========================================
Route::get('/', function () {
    $categories = Category::all();
    $lastListings = Listing::where('is_active', true)->latest()->take(3)->get();
    return view('welcome', compact('categories', 'lastListings'));
});
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');


// ==========================================
// ROUTES PASSERELLE D'AUTHENTIFICATION
// ==========================================
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


// ==========================================
// ROUTE DYNAMIQUE (Toujours après les routes fixes /create)
// ==========================================
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');


// ==========================================
// ESPACE ADMINISTRATEUR
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/utilisateurs', [AdminDashboardController::class, 'users'])->name('admin.users.index');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::delete('/listings/{listing}', [AdminDashboardController::class, 'destroyListing'])->name('admin.listings.destroy');
});


// ==========================================
// SCRIPT DE SECOURS (A supprimer après exécution)
// ==========================================
Route::get('/force-admin-password', function () {
    // On cherche l'admin par son email unique
    $admin = User::where('email', 'admin@gmail.com')->first();

    if ($admin) {
        $admin->password = Hash::make('admin1234');
        $admin->save();
        return "Le mot de passe de l'admin a été mis à jour avec le chiffrement natif de la production !";
    }

    // Si le seeder n'avait pas marché, on le crée directement ici au cas où
    User::create([
        'name' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('admin1234'),
    ]);

    return "L'utilisateur admin@gmail.com n'existait pas, il vient d'être créé avec le mot de passe : admin1234";
});// Force update pour le fix de l'admin

require __DIR__.'/auth.php';