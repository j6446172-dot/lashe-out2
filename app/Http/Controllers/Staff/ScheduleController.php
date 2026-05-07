<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $staffId = Auth::id();
        
        // جلب البيانات من قاعدة البيانات مباشرة
        $scheduleFromDb = DB::table('staff_schedule')
            ->where('staff_id', $staffId)
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week')
            ->toArray();
        
        // إعداد مصفوفة نهائية تحتوي على بيانات لجميع الأيام (0 إلى 6)
        $schedule = [];
        for ($i = 0; $i <= 6; $i++) {
            if (isset($scheduleFromDb[$i])) {
                $schedule[$i] = $scheduleFromDb[$i];
            } else {
                // بيانات افتراضية إذا لم يكن هناك جدول مسجل
                $schedule[$i] = (object)[
                    'day_of_week' => $i,
                    'status' => ($i == 5 || $i == 6) ? 'dayoff' : 'active',
                    'start_time' => ($i == 5 || $i == 6) ? null : '10:00',
                    'end_time' => ($i == 5 || $i == 6) ? null : '18:00',
                ];
            }
        }
        
        $days = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        
        // تمرير البيانات إلى الـ View
        return view('staff.schedule', compact('schedule', 'days'));
    }
}