<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class CustomerController extends Controller
{
    /**
     * 👥 صفحة العملاء
     * 
     * تعرض:
     * - إجمالي العملاء والجدد والعائدين
     * - نقاط الولاء
     * - تصنيف VIP
     */
    public function customers()
    {
        $customers = User::where('role', 'customer')->get();
        $totalCustomers = $customers->count();
        $newCustomers = $customers->where('created_at', '>=', now()->startOfMonth())->count();
        $returningCustomers = $customers->filter(fn($c) => Booking::where('user_id', $c->id)->count() >= 2)->count();
        $totalLoyaltyPoints = $customers->sum('loyalty_points');
        $avgCustomerRating = Review::avg('rating') ?? 0;
        
        // بناء قائمة العملاء مع التفاصيل
        $customersList = [];
        foreach ($customers as $c) {
            $b = Booking::where('user_id', $c->id);
            $customersList[] = [
                'id' => $c->id, 'name' => $c->name, 'phone' => $c->phone,
                'first_booking' => $b->min('booking_date'), 
                'total_bookings' => $b->count(),
                'total_spent' => $b->where('status', 'confirmed')->sum('price'),
                'loyalty_points' => $c->loyalty_points ?? 0,
                'rating' => Review::where('user_id', $c->id)->avg('rating') ?? 0,
                'is_vip' => ($c->loyalty_points ?? 0) >= 100 // VIP إذا نقاط الولاء ≥ 100
            ];
        }
        return view('owner.customers', compact(
            'totalCustomers', 'newCustomers', 'returningCustomers', 
            'totalLoyaltyPoints', 'avgCustomerRating', 'customersList'
        ));
    }

    /**
     * 👁️ عرض تفاصيل عميلة (نافذة منبثقة)
     */
    public function customerDetail($id)
    {
        $c = User::find($id); 
        if (!$c || $c->role !== 'customer') return response()->json(['error' => 'غير موجود']);
        $b = Booking::where('user_id', $id);
        return response()->json([
            'id' => $c->id, 'name' => $c->name, 'email' => $c->email, 'phone' => $c->phone,
            'total_bookings' => $b->count(), 
            'total_spent' => number_format($b->where('status', 'confirmed')->sum('price')),
            'loyalty_points' => $c->loyalty_points ?? 0,
            'rating' => number_format(Review::where('user_id', $id)->avg('rating') ?? 0, 1),
            'recent_bookings' => $b->with('staff')->latest()->take(5)->get()
                ->map(fn($x) => ['date' => $x->booking_date, 'service' => $x->service, 'staff' => $x->staff->name ?? '—'])
        ]);
    }
}