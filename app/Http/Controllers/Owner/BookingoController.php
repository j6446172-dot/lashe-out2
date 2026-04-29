<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class BookingController extends Controller
{
    /**
     * 📅 صفحة الحجوزات
     * 
     * تعرض جميع حجوزات الصالون مع إمكانية:
     * - البحث باسم العميلة
     * - الفلترة حسب الحالة (مؤكد/معلق/ملغي)
     * - الفلترة حسب الموظفة
     */
    public function bookings()
    {
        // 🔍 بناء استعلام البحث مع الفلاتر
        $query = Booking::query();
        
        // فلترة بالبحث عن اسم العميلة
        if (request('search')) { 
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . request('search') . '%')); 
        }
        
        // فلترة حسب حالة الحجز
        if (request('status')) { 
            $query->where('status', request('status')); 
        }
        
        // فلترة حسب الموظفة
        if (request('staff')) { 
            $query->where('staff_id', request('staff')); 
        }

        // 📊 إحصائيات الحجوزات
        $totalBookings = $query->count();
        $confirmedCount = (clone $query)->where('status', 'confirmed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $cancelledCount = (clone $query)->where('status', 'cancelled')->count();
        $tomorrowCount = (clone $query)->whereDate('booking_date', today()->addDay())->count();
        
        // 🕐 وقت الذروة (الأكثر حجوزات)
        $peakTime = (clone $query)->selectRaw('booking_time, COUNT(*) as count')
            ->groupBy('booking_time')->orderByDesc('count')->first();
        $peakTime = $peakTime ? $peakTime->booking_time : '—';
        
        // 📋 جميع الحجوزات مع بيانات العميلة والموظفة
        $allBookings = (clone $query)->with(['user', 'staff'])->latest()->paginate(20);
        $staffList = User::where('role', 'staff')->get(); // قائمة الموظفات للفلترة
        
        return view('owner.bookings', compact(
            'totalBookings', 'confirmedCount', 'pendingCount', 'cancelledCount', 
            'tomorrowCount', 'peakTime', 'allBookings', 'staffList'
        ));
    }

    /**
     * 👁️ عرض تفاصيل حجز معين (نافذة منبثقة)
     * 
     * ترجع بيانات الحجز كـ JSON للعرض في النافذة المنبثقة
     */
    public function bookingDetail($id)
    {
        // 🔍 جلب الحجز مع بيانات العميلة والموظفة
        $b = Booking::with(['user', 'staff'])->find($id);
        if (!$b) return response()->json(['error' => 'غير موجود']);
        
        // 📤 إرجاع البيانات كـ JSON
        return response()->json([
            'id' => $b->id,
            'user' => [
                'name' => $b->user->name ?? '', 
                'email' => $b->user->email ?? '', 
                'phone' => $b->user->phone ?? ''
            ],
            'service' => $b->service, 
            'price' => $b->price, 
            'staff' => ['name' => $b->staff->name ?? ''],
            'staff_rating' => Review::where('staff_id', $b->staff_id)->avg('rating') ?? 0,
            'booking_date' => $b->booking_date, 
            'booking_time' => $b->booking_time, 
            'status' => $b->status, 
            'notes' => $b->notes ?? ''
        ]);
    }
}