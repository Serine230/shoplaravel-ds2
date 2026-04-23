<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Récupérer les derniers contacts
        $contacts = User::whereIn('id', function ($query) use ($userId) {
            $query->select(DB::raw("CASE WHEN sender_id = {$userId} THEN receiver_id ELSE sender_id END"))
                  ->from('messages')
                  ->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })->where('id', '!=', $userId)->get();

        return view('messages.index', compact('contacts'));
    }

    public function conversation(User $user)
    {
        $myId = auth()->id();

        $messages = Message::where(function ($q) use ($myId, $user) {
            $q->where('sender_id', $myId)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($myId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $myId);
        })->orderBy('created_at')->get();

        // Marquer comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $myId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function send(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        return back();
    }
}
