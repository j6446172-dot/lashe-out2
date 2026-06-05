<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Review;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * 🏠 لوحة التحكم الرئيسية (نظرة عامة)
     * 
     * تعرض:
     * - إحصائيات عامة (العملاء، الإيرادات، صافي الربح)
     * - رسوم بيانية (نمو العملاء، أداء الموظفات)
     * - حجوزات اليوم
     */
    public function dashboard()
    {
        // 🔔 فحص نسبة الإلغاء للموظفات (لإرسال تنبيهات تلقائية)
        (new \App\Http\Controllers\OwnerController)->checkHighCancelRate();

        // 📊 بيانات الرسم البياني للإيرادات الشهرية (آخر 6 أشهر)
        $chartMonths = []; 
        $chartRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonths[] = $date->format('M');
            $chartRevenue[] = Booking::whereMonth('booking_date', $date->month)
                ->whereYear('booking_date', $date->year)
                ->where('status', 'confirmed')
                ->sum('price') ?? 0;
        }

        // 📈 بيانات نمو العملاء (عدد العملاء الجدد كل شهر)
        $customerGrowthMonths = []; 
        $customerGrowthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $customerGrowthMonths[] = $date->format('M');
            $customerGrowthData[] = User::where('role', 'customer')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        // 👥 إجمالي العملاء المسجلين
        $totalCustomers = User::where('role', 'customer')->count();
        
        // 💰 إيرادات الشهر الحالي
        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->where('status', 'confirmed')
            ->sum('price') ?? 0;
            
        // 🧮 حساب المصاريف الشهرية
        $staffCount = User::where('role', 'staff')->count();
        $salaries = $staffCount * 350;      // رواتب الموظفات
        $materials = $monthlyRevenue * 0.06; // تكلفة المواد (6%)
        $rent = 60;                          // إيجار وفواتير
        $netProfit = $monthlyRevenue - $salaries - $materials - $rent; // صافي الربح
        
        // 📊 إحصائيات إضافية
        $returnRate = (new \App\Http\Controllers\OwnerController)->calculateReturnRate(); // نسبة العملاء العائدين
        $averageRating = Review::avg('rating') ?? 0; // متوسط التقييمات
        $todayBookings = Booking::whereDate('booking_date', today())->count(); // حجوزات اليوم
        $todayBookingsList = Booking::with(['user', 'staff'])
            ->whereDate('booking_date', today())
            ->orderBy('booking_time')->take(5)->get(); // آخر 5 حجوزات اليوم
            
        // 👩‍💼 أداء الموظفات (للرسم البياني)
        $staffPerformance = (new \App\Http\Controllers\OwnerController)->getStaffPerformance();



        // 📋 طلبات الإجازة المعلقة
$pendingLeaves = LeaveRequest::where('status', 'pending')
    ->with('staff')
    ->latest()
    ->get();

$pendingLeavesCount = $pendingLeaves->count();


$unreadStaffMessages = ChatMessage::where('to_user_id', request()->user()->id)
    ->where('is_read', false)
    ->count();

// إرسال جميع البيانات للصفحة
return view('owner.dashboard', compact(
    'totalCustomers',
    'monthlyRevenue',
    'netProfit',
    'returnRate',
    'averageRating',
    'todayBookings',
    'todayBookingsList',
    'staffPerformance',
    'salaries',
    'materials',
    'rent',
    'chartMonths',
    'chartRevenue',
    'customerGrowthMonths',
    'customerGrowthData',
    'pendingLeaves',
    'pendingLeavesCount',
    'unreadStaffMessages'
));

    }
public function approveLeave(int $id)
{
    $leave = LeaveRequest::findOrFail($id);
    $leave->update(['status' => 'approved', 'reviewed_at' => now()]);
    
    $staff = User::findOrFail($leave->staff_id);
    $start = \Carbon\Carbon::parse($leave->start_date);
    $end = \Carbon\Carbon::parse($leave->end_date);
    $totalDays = (int)($start->diffInDays($end)) + 1;
    
    // تحديث دوام الموظفة
    $current = $start->copy();
    while ($current->lte($end)) {
        $dayIndex = ($current->dayOfWeek + 1) % 7;
        if ($dayIndex != 6) {
            \DB::table('staff_schedule')
                ->where('staff_id', $leave->staff_id)
                ->where('day_of_week', $dayIndex)
                ->update([
                    'status' => $leave->leave_type,
                    'start_time' => null,
                    'end_time' => null,
                    'updated_at' => now()
                ]);
        }
        $current->addDay();
    }
    
    // خصم الرصيد
    if ($leave->leave_type == 'سنوية') {
        $current = $staff->remaining_annual_leave ?? 14;
        if ($current >= $totalDays) {
            User::where('id', $leave->staff_id)
                ->update(['remaining_annual_leave' => DB::raw("remaining_annual_leave - {$totalDays}")]);
        } else {
            return back()->with('error', 'رصيد الإجازات السنوية غير كاف');
        }
    } elseif ($leave->leave_type == 'مرضية') {
        $current = $staff->remaining_sick_leave ?? 7;
        if ($current >= $totalDays) {
            User::where('id', $leave->staff_id)
                ->update(['remaining_sick_leave' => DB::raw("remaining_sick_leave - {$totalDays}")]);
        } else {
            return back()->with('error', 'رصيد الإجازات المرضية غير كاف');
        }
    } elseif ($leave->leave_type == 'طارئة') {
        $current = $staff->remaining_emergency_leave ?? 3;
        if ($current >= $totalDays) {
            User::where('id', $leave->staff_id)
                ->update(['remaining_emergency_leave' => DB::raw("remaining_emergency_leave - {$totalDays}")]);
        } else {
            return back()->with('error', 'رصيد الإجازات الطارئة غير كاف');
        }
    }
    
    return redirect()->route('owner.staff')->with('success', 'تمت الموافقة ✅ وخصم ' . $totalDays . ' يوم');
    
}
public function rejectLeave(int $id)
{
    LeaveRequest::find($id)->update(['status' => 'rejected']);
    return back()->with('success', 'تم الرفض ❌');
}

public function leaveDetails($id)
{
    $leave = \App\Models\LeaveRequest::with('staff')->findOrFail($id);
    return response()->json([
        'staff_name' => $leave->staff->name,
        'leave_type' => $leave->leave_type,
        'display_text' => $leave->display_text,
        'reason' => $leave->reason,
        'hours' => $leave->hours,
        'status' => $leave->status_name
    ]);
}

}