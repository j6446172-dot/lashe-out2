<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * عرض لوحة تحكم الموظف
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $staffId = (int) Auth::id();
        $today = date('Y-m-d');
        
        // حجوزات اليوم
        $todayBookings = Booking::where('staff_id', $staffId)
            ->where('booking_date', $today)
            ->count();
        
        // الحجوزات القادمة
        $upcomingBookings = Booking::where('staff_id', $staffId)
            ->where('booking_date', '>', $today)
            ->where('status', 'confirmed')
            ->count();
        
        // الحجوزات المكتملة
        $completedBookings = Booking::where('staff_id', $staffId)
            ->where('status', 'completed')
            ->count();
        
        // تقييمي
        $myRating = DB::table('reviews')
            ->where('staff_id', $staffId)
            ->avg('rating');
        
        $myRatingValue = (float) ($myRating ?? 0);
        $myRatingDisplay = $myRating ? number_format($myRating, 1) : 'جديد';
        
        // 🔥 تصحيح مهم: استخدام 'user' بدلاً من 'customer'
        $bookings = Booking::with('user')
            ->where('staff_id', $staffId)
            ->where('booking_date', $today)
            ->orderBy('booking_time')
            ->get();
        
        // جدول دوام الموظفة
        $schedule = [];
        for ($i = 0; $i <= 6; $i++) {
            $scheduleData = DB::table('staff_schedule')
                ->where('staff_id', $staffId)
                ->where('day_of_week', $i)
                ->first();
            $schedule[$i] = $scheduleData;
        }
        
        // حجوزات الشهر
        $monthlyBookings = Booking::where('staff_id', $staffId)
            ->whereMonth('booking_date', date('m'))
            ->count();
        
        return view('staff.dashboard', compact(
            'todayBookings', 'upcomingBookings', 'completedBookings',
            'myRatingDisplay', 'myRatingValue', 'bookings', 'schedule', 'monthlyBookings'
        ));
    }
}