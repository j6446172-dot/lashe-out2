<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getStaffMessages(int $staffId)
    {
        $messages = ChatMessage::where(function ($q) use ($staffId) {
                $q->where('from_user_id', Auth::id())
                  ->where('to_user_id', $staffId);
            })
            ->orWhere(function ($q) use ($staffId) {
                $q->where('from_user_id', $staffId)
                  ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendToStaff(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id'
        ]);

        $message = ChatMessage::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function getUnreadCount()
    {
        $count = ChatMessage::where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

  public function markAsRead(Request $request)
{
    $query = ChatMessage::where('to_user_id', Auth::id())
        ->where('is_read', false);

    if ($request->staff_id) {
        $query->where('from_user_id', $request->staff_id);
    }

    $query->update(['is_read' => true]);

    return response()->json(['success' => true]);
}
}