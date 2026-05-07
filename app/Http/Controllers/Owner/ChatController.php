<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * جلب رسائل الشات مع موظف معين
     * 
     * @param int $staffId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaffMessages(int $staffId)  // <-- أضفنا int قبل $staffId
    {
        $messages = ChatMessage::where(function($query) use ($staffId) {
                $query->where('from_user_id', Auth::id())->where('to_user_id', $staffId);
            })->orWhere(function($query) use ($staffId) {
                $query->where('from_user_id', $staffId)->where('to_user_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        
        return response()->json($messages);
    }

    /**
     * إرسال رسالة إلى موظف
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * جلب عدد الرسائل غير المقروءة من الموظفين
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        $count = ChatMessage::where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * تحديث حالة الرسائل كمقروءة
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'staff_id' => 'nullable|exists:users,id'
        ]);

        if ($request->has('staff_id')) {
            ChatMessage::where('to_user_id', Auth::id())
                ->where('from_user_id', $request->staff_id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            ChatMessage::where('to_user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }
}