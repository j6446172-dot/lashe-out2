<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private function getOwner()
    {
        return User::where('role', 'owner')->first();
    }

    public function index()
    {
        $owner = $this->getOwner();

        $messages = [];

        if ($owner) {
            $messages = ChatMessage::where(function ($q) use ($owner) {
                    $q->where('from_user_id', Auth::id())
                      ->where('to_user_id', $owner->id);
                })
                ->orWhere(function ($q) use ($owner) {
                    $q->where('from_user_id', $owner->id)
                      ->where('to_user_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $owner = $this->getOwner();

        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found'
            ], 404);
        }

        $message = ChatMessage::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $owner->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'user_name' => Auth::user()->name
        ]);
    }

    public function getMessages()
    {
        $owner = $this->getOwner();

        if (!$owner) {
            return response()->json([]);
        }

        $messages = ChatMessage::where(function ($q) use ($owner) {
                $q->where('from_user_id', Auth::id())
                  ->where('to_user_id', $owner->id);
            })
            ->orWhere(function ($q) use ($owner) {
                $q->where('from_user_id', $owner->id)
                  ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

   public function markAsRead()
{
    $owner = $this->getOwner();

    ChatMessage::where('to_user_id', Auth::id())
        ->where('from_user_id', $owner?->id)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

    public function getUnreadCount()
    {
        $count = ChatMessage::where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}