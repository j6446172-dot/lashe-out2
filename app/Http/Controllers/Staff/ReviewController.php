<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $staffId = Auth::id();
        
        // استخدام Eloquent مع العلاقات
        $reviews = Review::with(['booking', 'customer'])
            ->where('staff_id', $staffId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $avgRating = Review::where('staff_id', $staffId)->avg('rating');
        
        return view('staff.reviews', compact('reviews', 'avgRating'));
    }
}