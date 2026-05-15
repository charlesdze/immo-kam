<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'listings_count' => Listing::count(),
            'messages_count' => Message::count(),
            'unread_messages_count' => Message::where('is_read', false)->count(),
        ];
        $recentListings = Listing::with(['category', 'user'])->latest()->take(6)->get();
        return view('admin.index', compact('stats', 'recentListings'));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

  public function messages()
{
    $messages = Message::where('receiver_id', Auth::id())
        ->orWhere('sender_id', Auth::id())
        ->with(['sender', 'receiver', 'listing'])
        ->latest()
        ->paginate(10); // Utilise paginate au lieu de get

    return view('messages.index', compact('messages'));
}

    public function showMessage(Message $message)
    {
        if ($message->receiver_id !== Auth::id() && $message->sender_id !== Auth::id()) {
            abort(403);
        }
        if ($message->receiver_id === Auth::id()) {
            $message->update(['is_read' => true]);
        }
        return view('messages.show', compact('message'));
    }

    public function destroyUser(User $user) { $user->delete(); return back(); }
    public function destroyListing(Listing $listing) { $listing->delete(); return back(); }
}