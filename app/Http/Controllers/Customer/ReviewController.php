<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('booking.staff')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('customer.reviews.index', compact('reviews'));
    }

    public function create(int $bookingId)  // ✅ أضف int قبل $bookingId
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $bookingId)
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere(function($q) {
                          $q->where('status', 'confirmed')
                            ->where('booking_date', '<', date('Y-m-d'));
                      });
            })
            ->firstOrFail();
            
        return view('customer.reviews.create', compact('booking'));
    }

    public function store(Request $request, int $bookingId)  // ✅ أضف int قبل $bookingId
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $bookingId)
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere(function($q) {
                          $q->where('status', 'confirmed')
                            ->where('booking_date', '<', date('Y-m-d'));
                      });
            })
            ->firstOrFail();

        // منع التقييم المكرر
        $existing = Review::where('booking_id', $booking->id)->first();
        if ($existing) {
            return back()->with('error', 'لقد قمت بتقييم هذه الخدمة بالفعل');
        }

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'staff_id' => $booking->staff_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('customer.reviews.index')
            ->with('success', '⭐ شكراً لتقييمك! رأيك يساعدنا في تحسين الخدمة');
    }
}