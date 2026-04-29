<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ScheduleController extends Controller
{
    /**
     * 🕐 صفحة أوقات الدوام
     */
    public function schedule() 
    { 
        $staffMembers = User::where('role', 'staff')->get(); 
        return view('owner.schedule', compact('staffMembers')); 
    }

    /**
     * 💾 حفظ دوام الصالون (وتحديث دوام الموظفات تلقائياً)
     */
    public function updateSalonSchedule(Request $request)
    {
        foreach ($request->status as $index => $status) {
            // تحديث جدول دوام الصالون
            \DB::table('salon_schedule')->updateOrInsert(
                ['day_of_week' => $index],
                ['is_open' => $status == 'open' ? 1 : 0, 
                 'start_time' => $request->start[$index] ?? '10:00', 
                 'end_time' => $request->end[$index] ?? '18:00', 
                 'updated_at' => now()]
            );
            
            // إذا اليوم عطلة ← نخلي كل الموظفات عطلة
            if ($status == 'closed') {
                \DB::table('staff_schedule')->where('day_of_week', $index)
                    ->update(['status' => 'dayoff', 'start_time' => null, 'end_time' => null, 'updated_at' => now()]);
            }
            
            // إذا اليوم دوام ← نرجع الموظفات دوام
            if ($status == 'open') {
                \DB::table('staff_schedule')->where('day_of_week', $index)
                    ->update(['status' => 'active', 
                             'start_time' => $request->start[$index] ?? '10:00', 
                             'end_time' => $request->end[$index] ?? '18:00', 
                             'updated_at' => now()]);
            }
        }
        return back()->with('success', 'تم حفظ دوام الصالون ✅');
    }

    /**
     * 💾 حفظ دوام موظفة محدد
     */
    public function saveStaffSchedule(Request $request)
    {
        \DB::table('staff_schedule')->updateOrInsert(
            ['staff_id' => $request->staff_id, 'day_of_week' => $request->day_of_week],
            ['status' => $request->status, 'start_time' => $request->start_time, 
             'end_time' => $request->end_time, 'updated_at' => now()]
        );
        return response()->json(['success' => true]);
    }

    /**
     * 📅 عرض دوام موظفة محدد
     */
    public function getStaffSchedule($id)
    {
        $day = request('day', 0);
        $schedule = \DB::table('staff_schedule')
            ->where('staff_id', $id)->where('day_of_week', $day)->first();
        return response()->json($schedule ?? ['status' => 'active', 'start_time' => '10:00', 'end_time' => '18:00']);
    }
}