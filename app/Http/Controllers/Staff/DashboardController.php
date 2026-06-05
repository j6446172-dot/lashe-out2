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

        // حجوزات اليوم (بدون الملغية)
        $todayBookings = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        // الحجوزات المكتملة اليوم
        $completedBookings = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $today)
            ->where('status', 'completed')
            ->count();

        // الحجوزات المتبقية اليوم
        $remainingBookings = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        // تقييمي
        $myRating = DB::table('reviews')
            ->where('staff_id', $staffId)
            ->avg('rating');

        $myRatingValue = (float) ($myRating ?? 0);
        $myRatingDisplay = $myRating ? number_format($myRating, 1) : 'جديد';

        // حجوزات اليوم (تفاصيل)
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

        // بيانات الراتب
        $staff = User::find($staffId);

        $staffSalary = [
            'base_salary' => $staff->base_salary ?? 350,
            'deduction' => $staff->deduction ?? 0,
            'bonus' => $staff->bonus ?? 0,
            'net_salary' => ($staff->base_salary ?? 350)
                - ($staff->deduction ?? 0)
                + ($staff->bonus ?? 0)
        ];

        // إشعارات الإجازات
        $leaveNotifications = \App\Models\LeaveRequest::where('staff_id', $staffId)
            ->whereIn('status', ['approved', 'rejected'])
            ->where('notification_read', false)
            ->latest()
            ->get();

        return view('staff.dashboard', compact(
            'todayBookings',
            'completedBookings',
            'remainingBookings',
            'myRatingDisplay',
            'myRatingValue',
            'bookings',
            'schedule',
            'monthlyBookings',
            'staffSalary',
            'leaveNotifications'
        ));
    }

    
    



    public function monthlyStats()
{
    return view('staff.monthly-stats');
}

public function getMonthlyStatsData(Request $request)
{
    $staffId = auth()->id();
    $month = $request->get('month', date('Y-m'));
    $year = substr($month, 0, 4);
    $monthNum = substr($month, 5, 2);
    
    // Current month stats
    $currentBookings = Booking::where('staff_id', $staffId)
        ->whereYear('booking_date', $year)
        ->whereMonth('booking_date', $monthNum)
        ->get();
    
    // Previous month stats for comparison
    $prevMonth = date('Y-m', strtotime("$month -1 month"));
    $prevYear = substr($prevMonth, 0, 4);
    $prevMonthNum = substr($prevMonth, 5, 2);
    
    $prevBookings = Booking::where('staff_id', $staffId)
        ->whereYear('booking_date', $prevYear)
        ->whereMonth('booking_date', $prevMonthNum)
        ->get();
    
    // Calculate percentages
    $total = $currentBookings->count();
    $completed = $currentBookings->where('status', 'completed')->count();
    $cancelled = $currentBookings->where('status', 'cancelled')->count();
    $noshow = $currentBookings->where('status', 'no_show')->count();
    $pending = $currentBookings->where('status', 'pending')->count();
    
    $prevTotal = $prevBookings->count();
    $prevCompleted = $prevBookings->where('status', 'completed')->count();
    $prevCancelled = $prevBookings->where('status', 'cancelled')->count();
    $prevNoshow = $prevBookings->where('status', 'no_show')->count();
    
    // Daily data for chart
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
    $dailyCounts = [];
    $dailyDates = [];
    
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $date = sprintf('%04d-%02d-%02d', $year, $monthNum, $i);
        $dailyDates[] = $i;
        $dailyCounts[] = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $date)
            ->count();
    }
    
    // Service distribution
    $serviceData = [
        'labels' => [],
        'counts' => []
    ];
    
    $services = $currentBookings->groupBy('service_type');
    foreach ($services as $service => $bookings) {
        $serviceData['labels'][] = $service ?: 'خدمة أخرى';
        $serviceData['counts'][] = $bookings->count();
    }
    
    // Weekly comparison
    $weeklyData = $this->getWeeklyComparison($staffId);
    
    return response()->json([
        'total' => $total,
        'completed' => $completed,
        'cancelled' => $cancelled,
        'noshow' => $noshow,
        'pending' => $pending,
        'totalChange' => $prevTotal ? round(($total - $prevTotal) / $prevTotal * 100) : 0,
        'completedChange' => $prevCompleted ? round(($completed - $prevCompleted) / $prevCompleted * 100) : 0,
        'cancelledChange' => $prevCancelled ? round(($cancelled - $prevCancelled) / $prevCancelled * 100) : 0,
        'noshowChange' => $prevNoshow ? round(($noshow - $prevNoshow) / $prevNoshow * 100) : 0,
        'attendanceRate' => $total ? round(($completed + $pending) / $total * 100) : 0,
        'noshowRate' => $total ? round(($cancelled + $noshow) / $total * 100) : 0,
        'attendanceCount' => $completed + $pending,
        'noshowCount' => $cancelled + $noshow,
        'dailyData' => [
            'dates' => $dailyDates,
            'counts' => $dailyCounts
        ],
        'serviceData' => $serviceData,
        'weeklyData' => $weeklyData
    ]);
}

private function getWeeklyComparison($staffId)
{
    $thisWeek = [];
    $lastWeek = [];
    
    $now = now();
    $startOfWeek = $now->copy()->startOfWeek();
    $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
    
    for ($i = 0; $i < 7; $i++) {
        $thisWeek[] = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $startOfWeek->copy()->addDays($i))
            ->count();
        
        $lastWeek[] = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $startOfLastWeek->copy()->addDays($i))
            ->count();
    }
    
    return [
        'thisWeek' => $thisWeek,
        'lastWeek' => $lastWeek
    ];
}





    public function markNotificationRead($id)
    {
        $notification = \App\Models\LeaveRequest::where('id', $id)
            ->where('staff_id', auth()->id())
            ->firstOrFail();

        $notification->update([
            'notification_read' => true
        ]);

        return back();
    }
}