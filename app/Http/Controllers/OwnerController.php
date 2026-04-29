<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class OwnerController extends Controller
{
    /**
     * 🔔 فحص نسبة الإلغاء المرتفعة للموظفات
     * 
     * إذا تجاوزت نسبة الإلغاء 15%، يتم إرسال تنبيه تلقائي للمالك
     */
    public function checkHighCancelRate()
    {
        $staffWithHighCancel = User::where('role', 'staff')->get()->filter(function($s) {
            $total = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->count();
            $cancelled = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count();
            return $total > 0 && ($cancelled / $total) * 100 > 15;
        });
        
        foreach ($staffWithHighCancel as $staff) {
            // نتأكد إن الإشعار مش موجود من قبل
            $exists = \DB::table('notifications')
                ->where('type', 'high_cancel')->where('owner_id', 1)
                ->whereDate('created_at', today())->exists();
                
            if (!$exists) {
                \DB::table('notifications')->insert([
                    'owner_id' => 1, 'type' => 'high_cancel',
                    'title' => '⚠️ نسبة إلغاء مرتفعة',
                    'message' => "{$staff->name} لديها نسبة إلغاء مرتفعة هذا الشهر",
                    'created_at' => now(), 'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * 📊 حساب نسبة العملاء العائدين
     * 
     * العملاء الذين حجزوا مرتين أو أكثر
     */
    public function calculateReturnRate()
    {
        $t = User::where('role', 'customer')->count(); 
        if ($t == 0) return 0;
        $r = 0; 
        foreach (User::where('role', 'customer')->get() as $c) { 
            if (Booking::where('user_id', $c->id)->where('status', 'confirmed')->count() >= 2) $r++; 
        }
        return round(($r / $t) * 100);
    }

    /**
     * 👩‍💼 حساب أداء الموظفات للرسوم البيانية
     * 
     * تجمع عدد الحجوزات، المكتملة، الملغية، والإيرادات لكل موظفة
     */
    public function getStaffPerformance()
    {
        $p = []; 
        foreach (User::where('role', 'staff')->get() as $s) { 
            $t = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->count();
            $co = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->count();
            $ca = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count();
            $rv = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->sum('price') ?? 0;
            $p[] = [
                'name' => $s->name, 'total_bookings' => $t, 
                'completed' => $co, 'cancelled' => $ca, 
                'cancel_rate' => $t > 0 ? round(($ca / $t) * 100, 1) : 0, 
                'rating' => Review::where('staff_id', $s->id)->avg('rating') ?? 0, 
                'revenue' => $rv
            ]; 
        } 
        return $p;
    }
}