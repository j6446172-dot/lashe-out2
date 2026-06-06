<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // أضيفي هذا

class BookingController extends Controller
{
    public function index()
    {
        $staffId = Auth::id();
        
        $bookings = Booking::with('user')
            ->where('staff_id', $staffId)
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'asc')
            ->paginate(20);
        
        return view('staff.bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);
        
        $oldStatus = $booking->status;
        $booking->update($validated);
        
        // إذا تم تغيير الحالة إلى 'completed'، أضف النقاط
        if ($validated['status'] == 'completed' && $oldStatus != 'completed') {
            
            // 🔥 إضافة 10 نقاط للعميلة
            $userId = $booking->user_id;
            $existing = DB::table('loyalty_points')->where('user_id', $userId)->first();
            
            if ($existing) {
                DB::table('loyalty_points')->where('user_id', $userId)->increment('points', 10);
            } else {
                DB::table('loyalty_points')->insert([
                    'user_id' => $userId,
                    'points' => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            return redirect()->route('staff.bookings')
                ->with('success', '✅ تم إكمال الخدمة وأضيفت 10 نقاط للعميلة بنجاح!');
        }
        
        return back()->with('success', 'تم تحديث حالة الحجز بنجاح');
    }
}