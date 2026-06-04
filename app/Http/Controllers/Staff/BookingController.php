<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

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
        
        // إذا تم تغيير الحالة إلى 'completed'، خلص وارجع لصفحة الموظفة
        if ($validated['status'] == 'completed' && $oldStatus != 'completed') {
            return redirect()->route('staff.bookings')
                ->with('success', '✅ تم إكمال الخدمة بنجاح!');
        }
        
        return back()->with('success', 'تم تحديث حالة الحجز بنجاح');
    }
}