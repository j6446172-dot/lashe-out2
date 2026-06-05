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
    public function dashboard()
    {
        (new \App\Http\Controllers\OwnerController)->checkHighCancelRate();

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

        $totalCustomers = User::where('role', 'customer')->count();
        
        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->where('status', 'confirmed')
            ->sum('price') ?? 0;
            
        $staffCount = User::where('role', 'staff')->count();
        $salaries = $staffCount * 350;
        $materials = $monthlyRevenue * 0.06;
        $rent = 60;
        $netProfit = $monthlyRevenue - $salaries - $materials - $rent;
        
        $returnRate = (new \App\Http\Controllers\OwnerController)->calculateReturnRate();
        $averageRating = Review::avg('rating') ?? 0;
        $todayBookings = Booking::whereDate('booking_date', today())->count();
        $todayBookingsList = Booking::with(['user', 'staff'])
            ->whereDate('booking_date', today())
            ->orderBy('booking_time')->take(5)->get();
            
        $staffPerformance = (new \App\Http\Controllers\OwnerController)->getStaffPerformance();

        $pendingLeaves = LeaveRequest::where('status', 'pending')
            ->with('staff')
            ->latest()
            ->get();
        $pendingLeavesCount = $pendingLeaves->count();

        $unreadStaffMessages = ChatMessage::where('to_user_id', request()->user()->id)
            ->where('is_read', false)
            ->count();

        return view('owner.dashboard', compact(
            'totalCustomers', 'monthlyRevenue', 'netProfit', 'returnRate',
            'averageRating', 'todayBookings', 'todayBookingsList', 'staffPerformance',
            'salaries', 'materials', 'rent', 'chartMonths', 'chartRevenue',
            'customerGrowthMonths', 'customerGrowthData', 'pendingLeaves',
            'pendingLeavesCount', 'unreadStaffMessages'
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
        
        if ($leave->leave_type == 'سنوية') {
            User::where('id', $leave->staff_id)
                ->update(['remaining_annual_leave' => DB::raw("remaining_annual_leave - {$totalDays}")]);
        } elseif ($leave->leave_type == 'مرضية') {
            User::where('id', $leave->staff_id)
                ->update(['remaining_sick_leave' => DB::raw("remaining_sick_leave - {$totalDays}")]);
        } elseif ($leave->leave_type == 'طارئة') {
            User::where('id', $leave->staff_id)
                ->update(['remaining_emergency_leave' => DB::raw("remaining_emergency_leave - {$totalDays}")]);
        }
        
        return redirect()->route('owner.schedule')->with('success', 'تمت الموافقة ✅');
    }
    public function rejectLeave(int $id)
    {
        LeaveRequest::find($id)->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);
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