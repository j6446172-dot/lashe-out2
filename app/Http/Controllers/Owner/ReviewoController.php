<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;

class ReviewoController extends Controller
{
    /**
     * ⭐ صفحة التقييمات
     * 
     * تعرض:
     * - متوسط التقييم وعدد التقييمات
     * - فلترة حسب النجوم أو الموظفة
     */
    public function reviews()
    {
        $query = Review::query();
        
        // فلترة حسب عدد النجوم
        if (request('rating')) { $query->where('rating', request('rating')); }
        
        // فلترة حسب الموظفة
        if (request('staff'))  { $query->where('staff_id', request('staff')); }
        
        // 📊 إحصائيات التقييمات
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();
        $maxRating = Review::max('rating') ?? 0;
        $todayReviews = Review::whereDate('created_at', today())->count();
        
        // 📋 جميع التقييمات مع بيانات العميلة والموظفة
        $reviews = $query->with(['user', 'staff', 'booking'])->latest()->paginate(20);
        $staffList = User::where('role', 'staff')->get();
        
        return view('owner.reviews', compact(
            'avgRating', 'totalReviews', 'maxRating', 'todayReviews', 'reviews', 'staffList'
        ));
    }
}