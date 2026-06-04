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
        
        // الحجوزات التي تحتاج تقييم (مكتملة وما عليها تقييم)
        $needReview = Auth::user()->bookings()
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->orderBy('booking_date', 'desc')
            ->get();
            
        return view('customer.reviews.index', compact('reviews', 'needReview'));
    }

    public function create(Booking $booking)
    {
        // التأكد أن الحجز يخص العميل المسجل
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'هذا الحجز ليس لك');
        }
        
        // التأكد أن الحجز مكتمل
        if ($booking->status !== 'completed') {
            return redirect()->route('customer.reviews.index')
                ->with('error', 'لا يمكن تقييم خدمة لم تكتمل بعد');
        }
        
        // التأكد أنه ما فيه تقييم مسبق
        if ($booking->review) {
            return redirect()->route('customer.reviews.index')
                ->with('error', 'تم تقييم هذه الخدمة مسبقاً');
        }
        
        return view('customer.reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // التأكد أن الحجز يخص العميل المسجل
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

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