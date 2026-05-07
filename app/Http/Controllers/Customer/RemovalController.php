<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class RemovalController extends Controller
{
    public function step1()
    {
        return view('customer.removal.step1');
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required',
        ]);

        // ✅ الحل: نجيب أول موظفة في النظام (أو أي رقم)
        $defaultStaff = User::where('role', 'staff')->first();
        
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'staff_id' => $defaultStaff ? $defaultStaff->id : 1, // ✅ رقم وهمي إذا ما في موظفة
            'service_type' => 'removal',
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'location' => 'salon',
            'price' => 5,
            'status' => 'confirmed',
            'eye_shape' => null,
            'style_preference' => null,
        ]);

        auth()->user()->increment('loyalty_points', 5);

        return redirect()->route('customer.bookings.show', $booking->id)
            ->with('success', '✅ تم حجز خدمة إزالة الرموش بنجاح!');
    }
}