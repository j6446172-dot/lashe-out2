<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $staffId = Auth::id();
        
        // Get filter type for each section from request
        $upcomingFilter = $request->get('upcoming_filter', 'all');
        $pastFilter = $request->get('past_filter', 'all');
        $cancelledFilter = $request->get('cancelled_filter', 'all');
        
        // ========== 1. القادمة (confirmed فقط) - استبعاد removal ==========
        $upcomingQuery = Booking::with('user')
            ->where('staff_id', $staffId)
            ->where('status', 'confirmed')
            ->where('service_type', '!=', 'removal');  // ✅ منع ظهور خدمة إزالة الرموش
        
        // تطبيق الفلتر حسب التاريخ
        switch ($upcomingFilter) {
            case 'today':
                $upcomingQuery->whereDate('booking_date', Carbon::today());
                break;
            case 'week':
                $upcomingQuery->whereBetween('booking_date', [Carbon::today(), Carbon::today()->endOfWeek()]);
                break;
            case 'all':
            default:
                break;
        }
        
        $upcomingBookings = $upcomingQuery->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();
        
        // ========== 2. السابقة (completed فقط) - استبعاد removal ==========
        $pastQuery = Booking::with('user')
            ->where('staff_id', $staffId)
            ->where('status', 'completed')
            ->where('service_type', '!=', 'removal');  // ✅ منع ظهور خدمة إزالة الرموش
        
        switch ($pastFilter) {
            case 'today':
                $pastQuery->whereDate('booking_date', Carbon::today());
                break;
            case 'week':
                $pastQuery->whereBetween('booking_date', [Carbon::today()->subWeek(), Carbon::today()]);
                break;
            case 'all':
            default:
                break;
        }
        
        $pastBookings = $pastQuery->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();
        
        // ========== 3. الملغية (cancelled فقط) - استبعاد removal ==========
        $cancelledQuery = Booking::with('user')
            ->where('staff_id', $staffId)
            ->where('status', 'cancelled')
            ->where('service_type', '!=', 'removal');  // ✅ منع ظهور خدمة إزالة الرموش
        
        switch ($cancelledFilter) {
            case 'today':
                $cancelledQuery->whereDate('booking_date', Carbon::today());
                break;
            case 'week':
                $cancelledQuery->whereBetween('booking_date', [Carbon::today()->subWeek(), Carbon::today()]);
                break;
            case 'all':
            default:
                break;
        }
        
        $cancelledBookings = $cancelledQuery->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();
        
        return view('staff.bookings', compact(
            'upcomingBookings', 
            'pastBookings', 
            'cancelledBookings',
            'upcomingFilter',
            'pastFilter',
            'cancelledFilter'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // ✅ منع تعديل حالة حجوزات إزالة الرموش
        if ($booking->service_type == 'removal') {
            return redirect()->route('staff.bookings')->with('error', '❌ لا يمكن تعديل حالة حجوزات إزالة الرموش، تتم تلقائياً');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:completed,cancellgit inited'
        ]);
        
        $booking->status = $validated['status'];
        $booking->save();
        
        if ($validated['status'] == 'completed') {
            $userId = $booking->user_id;
            
            $check = DB::table('loyalty_points')
                ->where('user_id', $userId)
                ->first();
            
            if ($check) {
                DB::table('loyalty_points')
                    ->where('user_id', $userId)
                    ->increment('points', 10);
            } else {
                DB::table('loyalty_points')->insert([
                    'user_id' => $userId,
                    'points' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $message = $validated['status'] == 'completed' 
            ? '✨ تم إكمال الخدمة بنجاح!' 
            : '❌ تم إلغاء الحجز!';
        
        return redirect()->route('staff.bookings')->with('success', $message);
    }
}