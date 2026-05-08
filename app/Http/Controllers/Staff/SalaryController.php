<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    // جلب تفاصيل الراتب للشهر الحالي
    public function current()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        $salary = SalaryHistory::where('staff_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();
        
        if (!$salary) {
            // بيانات افتراضية إذا لم يكن هناك راتب مسجل
            $salary = (object)[
                'base_salary' => 350,
                'deduction' => 0,
                'bonus' => 0,
                'net_salary' => 350,
                'month_name' => date('F'),
                'is_paid' => false
            ];
        }
        
        return response()->json($salary);
    }
    
    // جلب سجل الرواتب لآخر 12 شهر
    public function history()
    {
        $salaries = SalaryHistory::where('staff_id', Auth::id())
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get()
            ->map(function($salary) {
                $salary->month_name = $salary->month_name;
                return $salary;
            });
        
        return response()->json($salaries);
    }
    
    // ✅ أضف int قبل $year و int قبل $month
    public function show(int $year, int $month)
    {
        $salary = SalaryHistory::where('staff_id', Auth::id())
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        
        if (!$salary) {
            return response()->json(['error' => 'لا توجد بيانات لهذا الشهر'], 404);
        }
        
        return response()->json($salary);
    }
}