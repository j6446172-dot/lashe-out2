<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * عرض جميع حجوزات الموظف
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * تحديث حالة الحجز
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, int $id)
    {
        $booking = Booking::where('staff_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);
        
        $booking->update($validated);
        
        return back()->with('success', 'تم تحديث حالة الحجز بنجاح');
    }
}