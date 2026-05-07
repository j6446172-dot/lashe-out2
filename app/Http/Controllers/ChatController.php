<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // جلب المحادثات بين الموظف والمالك
        $ownerId = \App\Models\User::where('role', 'owner')->first()->id;
        
        $messages = ChatMessage::where(function($query) use ($ownerId) {
                $query->where('from_user_id', Auth::id())->where('to_user_id', $ownerId);
            })->orWhere(function($query) use ($ownerId) {
                $query->where('from_user_id', $ownerId)->where('to_user_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        
        return view('chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $ownerId = \App\Models\User::where('role', 'owner')->first()->id;

        $message = ChatMessage::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $ownerId,
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
        $ownerId = \App\Models\User::where('role', 'owner')->first()->id;
        
        $messages = ChatMessage::where(function($query) use ($ownerId) {
                $query->where('from_user_id', Auth::id())->where('to_user_id', $ownerId);
            })->orWhere(function($query) use ($ownerId) {
                $query->where('from_user_id', $ownerId)->where('to_user_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        
        return response()->json($messages);
    }

    public function markAsRead()
    {
        ChatMessage::where('to_user_id', Auth::id())
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