<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * انضمام إلى طابور الانتظار
     */
    public function join(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date|after:today',
            'service_type' => 'required|string',
        ]);
        
        // حساب رقم الطابور
        $existingQueue = Booking::where('booking_date', $request->booking_date)
            ->where('in_queue', true)
            ->max('queue_position');
            
        $position = ($existingQueue ?? 0) + 1;
        
        // إنشاء حجز في الطابور
        $queueBooking = Booking::create([
            'user_id' => auth()->id(),
            'staff_id' => null,
            'service_type' => $request->service_type,
            'booking_date' => $request->booking_date,
            'booking_time' => null,
            'location' => 'salon',
            'status' => 'pending',
            'in_queue' => true,
            'queue_position' => $position,
        ]);
        
        return redirect()->route('customer.queue.status', $queueBooking)
            ->with('success', 'تم إضافتك إلى طابور الانتظار! رقمك ' . $position);
    }
    
    /**
     * عرض حالة الطابور
     */
    public function status(Booking $queueBooking)
    {
        // التأكد أن هذا الحجز ملك للعميل الحالي
        if ($queueBooking->user_id !== auth()->id()) {
            abort(403);
        }
        
        // حساب الوقت المتوقع (30 دقيقة لكل شخص)
        $estimatedWait = $queueBooking->queue_position * 30;
        
        // عدد الأشخاص قبلك
        $peopleAhead = Booking::where('booking_date', $queueBooking->booking_date)
            ->where('in_queue', true)
            ->where('queue_position', '<', $queueBooking->queue_position)
            ->count();
        
        return view('customer.queue.waiting', compact('queueBooking', 'estimatedWait', 'peopleAhead'));
    }
    
    /**
     * التحقق من التحديث المباشر للطابور (AJAX)
     */
    public function checkStatus($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        if ($booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $peopleAhead = Booking::where('booking_date', $booking->booking_date)
            ->where('in_queue', true)
            ->where('queue_position', '<', $booking->queue_position)
            ->count();
        
        $currentPosition = $booking->queue_position - $peopleAhead;
        
        return response()->json([
            'position' => $currentPosition,
            'people_ahead' => $peopleAhead,
            'status' => $booking->status,
            'estimated_wait' => $peopleAhead * 30,
        ]);
    }
    
    /**
     * إلغاء الطابور
     */
    public function cancel(Booking $queueBooking)
    {
        if ($queueBooking->user_id !== auth()->id()) {
            abort(403);
        }
        
        $queueBooking->update([
            'status' => 'cancelled',
            'in_queue' => false,
            'queue_position' => null,
        ]);
        
        return redirect()->route('customer.dashboard')
            ->with('success', 'تم إلغاء طلب الانتظار');
    }
}