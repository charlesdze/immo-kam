<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // On initialise des compteurs par défaut à 0 pour parer à tout crash de table en production
        $usersCount = 0;
        $listingsCount = 0;
        $messagesCount = 0;

        try {
            if (Schema::hasTable('users')) {
                $usersCount = User::count();
            }
            if (Schema::hasTable('listings')) {
                $listingsCount = Listing::count();
            }
            if (Schema::hasTable('messages')) {
                $messagesCount = Message::count();
            }
        } catch (\Exception $e) {
            // Si une table manque en ligne, l'application ne plante pas
        }

        $stats = [
            'users_count'           => $usersCount,
            'listings_count'        => $listingsCount,
            'messages_count'        => $messagesCount,
            'unread_messages_count' => 0, 
        ];

        // Sécurisation de la récupération des annonces récentes
        $recentListings = collect();
        try {
            if (Schema::hasTable('listings')) {
                $recentListings = Listing::with(['category', 'user'])->latest()->take(6)->get();
            }
        } catch (\Exception $e) {
            // Évite le crash si les relations 'category' ou 'user' ont un problème en base de données
        }

        return view('admin.index', compact('stats', 'recentListings'));
    }

    public function users()
    {
        $users = collect();
        try {
            $users = User::latest()->paginate(10);
        } catch (\Exception $e) {
            // Protection contre un crash de la pagination sur Railway
        }
        return view('admin.users.index', compact('users'));
    }

    public function messages()
    {
        $messages = collect();
        
        try {
            if (Schema::hasTable('messages')) {
                // Utilisation d'une fonction de rappel (callback) pour isoler le 'orWhere' proprement
                $messages = Message::where(function ($query) {
                        $query->where('receiver_id', Auth::id())
                              ->orWhere('sender_id', Auth::id());
                    })
                    ->with(['sender', 'receiver', 'listing'])
                    ->latest()
                    ->paginate(10);
            }
        } catch (\Exception $e) {
            // Sécurité
        }

        // ATTENTION : Vérifie bien que ton fichier vue existe à cet endroit : resources/views/messages/index.blade.path
        // Si ta vue est dans le dossier admin, remplace par : return view('admin.messages.index', compact('messages'));
        return view('messages.index', compact('messages'));
    }

    public function showMessage(Message $message)
    {
        if ($message->receiver_id !== Auth::id() && $message->sender_id !== Auth::id()) {
            abort(403);
        }
        
        try {
            if ($message->receiver_id === Auth::id() && Schema::hasColumn('messages', 'is_read')) {
                $message->update(['is_read' => true]);
            }
        } catch (\Exception $e) {
            // Ignore l'erreur si la colonne is_read n'existe pas encore
        }

        return view('messages.show', compact('message'));
    }

    public function destroyUser(User $user) 
    { 
        try {
            $user->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cet utilisateur.');
        }
        return back(); 
    }

    public function destroyListing(Listing $listing) 
    { 
        try {
            $listing->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cette annonce.');
        }
        return back(); 
    }
}