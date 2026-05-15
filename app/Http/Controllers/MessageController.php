<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $messages = Message::where('receiver_id', auth()->id())
        ->with(['sender', 'listing'])
        ->latest()
        ->get();

    // On marque tous les messages reçus comme "lus"
    Message::where('receiver_id', auth()->id())->update(['is_read' => true]);

    return view('messages.index', compact('messages'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'receiver_id' => 'required|exists:users,id',
        'listing_id' => 'nullable|exists:listings,id',
    ]);

    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'listing_id' => $request->listing_id,
        'content' => $request->content,
        'is_read' => false,
    ]);

    return redirect()->route('admin.messages')->with('success', 'Votre message a été envoyé avec succès !');
}

    /**
     * Display the specified resource.
     */
    public function showMessage(Message $message)
{
    // Sécurité : vérifier que le message appartient bien à l'utilisateur
    if ($message->receiver_id !== Auth::id()) {
        abort(403);
    }

    // Marquer comme lu
    $message->update(['is_read' => true]);

    return view('admin.messages.show', compact($message));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
