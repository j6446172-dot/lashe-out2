<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class StaffController extends Controller
{
    /**
     * 👩‍💼 صفحة الموظفات
     * 
     * تعرض قائمة الموظفات مع:
     * - إحصائيات (نشطات، في إجازة، متوسط التقييم)
     * - تفاصيل مالية (راتب، خصم، مكافأة)
     */
    public function staff()
    {
        $staffMembers = User::where('role', 'staff')->get();
        $totalStaff = $staffMembers->count();
        $activeStaff = $staffMembers->where('status', 'active')->count(); // الموظفات النشطات
        $onLeaveStaff = $staffMembers->where('status', 'leave')->count(); // الموظفات في إجازة
        $avgRating = Review::whereIn('staff_id', $staffMembers->pluck('id'))->avg('rating') ?? 0;
        
        // بناء قائمة الموظفات مع كافة التفاصيل
        $staffList = [];
        foreach ($staffMembers as $staff) {
            $total = Booking::where('staff_id', $staff->id)
                ->whereMonth('booking_date', now()->month)->count();
            $cancelled = Booking::where('staff_id', $staff->id)
                ->whereMonth('booking_date', now()->month)
                ->where('status', 'cancelled')->count();
            $rating = Review::where('staff_id', $staff->id)->avg('rating') ?? 0;
            
            $staffList[] = [
                'id' => $staff->id, 
                'name' => $staff->name, 
                'specialty' => $staff->specialty ?? '—',
                'total_bookings' => $total,
                'cancel_rate' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0,
                'rating' => $rating, 
                'base_salary' => $staff->salary ?? 350,
                'deduction' => $staff->deduction ?? 0, 
                'bonus' => $staff->bonus ?? 0,
                'net_salary' => ($staff->salary ?? 350) + ($staff->bonus ?? 0) - ($staff->deduction ?? 0)
            ];
        }
        return view('owner.staff', compact('totalStaff', 'activeStaff', 'onLeaveStaff', 'avgRating', 'staffList'));
    }

    /**
     * 👁️ عرض تفاصيل موظفة (نافذة منبثقة)
     */
    public function staffDetail($id)
    {
        $s = User::find($id); 
        if (!$s || $s->role !== 'staff') return response()->json(['error' => 'غير موجود']);
        $t = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->count();
        $c = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count();
        $r = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->sum('price') ?? 0;
        return response()->json([
            'name' => $s->name, 'total_bookings' => $t,
            'cancel_rate' => $t > 0 ? round(($c / $t) * 100, 1) : 0,
            'rating' => number_format(Review::where('staff_id', $id)->avg('rating') ?? 0, 1),
            'revenue' => number_format($r), 'base_salary' => $s->salary ?? 350,
            'deduction' => $s->deduction ?? 0, 'bonus' => $s->bonus ?? 0,
            'net_salary' => ($s->salary ?? 350) + ($s->bonus ?? 0) - ($s->deduction ?? 0)
        ]);
    }

    /**
     * ➕ إضافة موظفة جديدة
     * 
     * ينشئ حساب جديد للموظفة بكلمة مرور افتراضية
     */
    public function addStaff(Request $request) 
    { 
        User::create([
            'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone,
            'password' => Hash::make('password'), 'role' => 'staff',
            'salary' => $request->salary ?? 350, 'specialty' => $request->specialty ?? ''
        ]); 
        return response()->json(['success' => true]); 
    }

    /**
     * ✏️ تعديل بيانات موظفة
     */
    public function updateStaff(Request $request, $id) 
    { 
        User::findOrFail($id)->update([
            'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone,
            'specialty' => $request->specialty, 'salary' => $request->salary
        ]); 
        return response()->json(['success' => true]); 
    }

    /**
     * 🗑️ حذف موظفة
     */
    public function deleteStaff($id) 
    { 
        User::findOrFail($id)->delete(); 
        return response()->json(['success' => true]); 
    }

    /**
     * 📅 عرض جدول دوام موظفة
     */
    public function staffSchedule($id) 
    { 
        $s = User::findOrFail($id); 
        return view('owner.staff-schedule', compact('s')); 
    }
}