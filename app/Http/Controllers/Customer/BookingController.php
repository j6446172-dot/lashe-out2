<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Queue;
use Carbon\Carbon;

class BookingController extends Controller
{
    // ================= Dashboard =================
    public function dashboard()
    {
        $user = auth()->user();
        
        // ✅ الخدمات المكتملة (completed فقط)
        $completedBookings = $user->bookings()
            ->where('status', 'completed')
            ->count();
        
        // ✅ إجمالي الحجوزات (confirmed + completed فقط، بدون cancelled)
        $totalBookings = $user->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        
        // ✅ الحجوزات القادمة
        $upcomingBookings = $user->bookings()
            ->where('booking_date', '>=', today())
            ->whereIn('status', ['confirmed', 'completed'])
            ->orderBy('booking_date')
            ->take(3)
            ->get();

        $loyaltyPoints = $user->loyalty_points ?? 0;

        return view('customer.dashboard', compact('upcomingBookings', 'loyaltyPoints', 'completedBookings', 'totalBookings'));
    }

    // ================= STEP 1 =================
    public function step1Style()
    {
        $user = auth()->user();
        $lastEyeShape = $user->last_eye_shape;
        $lastLashDuration = $user->last_lash_duration;
        
        return view('customer.bookings.step1-style', compact('lastEyeShape', 'lastLashDuration'));
    }

    public function postStep1(Request $request)
{
    $request->validate([
        'eye_shape' => 'required',
    ]);

    // ✅ المدة تخزن في session فقط (ما تحفظ في قاعدة بيانات المستخدم)
    $lashDuration = $request->has('lash_duration') ? $request->lash_duration : 'weekly';

    session([
        'booking.eye_shape' => $request->eye_shape,
        'booking.lash_duration' => $lashDuration,  // ✅ تخزين مؤقت فقط
    ]);

    // ✅ نحفظ شكل العين فقط في قاعدة بيانات المستخدم
    $user = auth()->user();
    $user->last_eye_shape = $request->eye_shape;
    $user->save();

    return redirect()->route('customer.bookings.step2');
}

    // ================= STEP 2 =================
    public function step2Service()
    {
        $services = [
            'classic' => ['name' => 'Classic Set', 'price' => 30, 'duration' => 60, 'description' => 'طبيعي وناعم', 'icon' => 'feather'],
            'wet' => ['name' => 'Wet Set', 'price' => 40, 'duration' => 75, 'description' => 'مظهر الرموش المبللة العصرية', 'icon' => 'tint'],
            'wispy' => ['name' => 'Wispy Set', 'price' => 50, 'duration' => 90, 'description' => 'تصميم ريشي متدرج', 'icon' => 'feather-alt'],
            'volume' => ['name' => 'Volume Set', 'price' => 45, 'duration' => 90, 'description' => 'كثافة عالية وسواد فاحم', 'icon' => 'layer-group'],
            'anime' => ['name' => 'Anime Set', 'price' => 55, 'duration' => 90, 'description' => 'ستايل الأنمي الشهير', 'icon' => 'star'],
        ];
        
        return view('customer.bookings.step2-service', compact('services'));
    }

    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:classic,wet,wispy,volume,anime'
        ]);

        session(['booking.service_type' => $validated['service_type']]);
        session()->save();

        return redirect()->route('customer.bookings.step3');
    }

    // ================= STEP 3 =================
    public function step3DateTime()
    {
        $availableDates = $this->getAvailableDates();
        $availableTimes = $this->getAvailableTimes();
        $suggestedDates = $this->getSuggestedDates();
        
        return view('customer.bookings.step3-datetime', compact('availableDates', 'availableTimes', 'suggestedDates'));
    }

    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required'
        ]);

        $existingBooking = Booking::where('user_id', auth()->id())
            ->where('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->where('status', 'confirmed')
            ->first();

        if ($existingBooking) {
            return back()->with('error', '⚠️ لديكِ بالفعل حجز في هذا الموعد!');
        }

        session([
            'booking.booking_date' => $validated['booking_date'],
            'booking.booking_time' => $validated['booking_time']
        ]);
        session()->save();

        return redirect()->route('customer.bookings.step4');
    }

    // ================= STEP 4 =================
    public function step4Staff()
    {
        if (!session()->has('booking.booking_date') || !session()->has('booking.booking_time')) {
            return redirect()->route('customer.bookings.step3')
                ->with('error', 'الرجاء اختيار التاريخ والوقت أولاً');
        }
        
        $allStaff = User::where('role', 'staff')->get();
        
        $busyStaffIds = Booking::where('booking_date', session('booking.booking_date'))
            ->where('booking_time', session('booking.booking_time'))
            ->where('status', 'confirmed')
            ->pluck('staff_id')
            ->toArray();
        
        $availableStaff = $allStaff->filter(function ($staff) use ($busyStaffIds) {
            return !in_array($staff->id, $busyStaffIds);
        });
        
        if ($availableStaff->isEmpty()) {
            return redirect()->route('customer.bookings.step3')
                ->with('error', '⚠️ جميع الموظفات مشغولات في هذا الوقت! الرجاء اختيار وقت آخر.');
        }
        
        return view('customer.bookings.step4-staff', compact('availableStaff'));
    }

    public function postStep4(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'location' => 'required|in:salon,home'
        ]);

        $existingStaffBooking = Booking::where('staff_id', $validated['staff_id'])
            ->where('booking_date', session('booking.booking_date'))
            ->where('booking_time', session('booking.booking_time'))
            ->where('status', 'confirmed')
            ->first();

        if ($existingStaffBooking) {
            return back()->with('error', '⚠️ عذراً، هذه الموظفة أصبحت مشغولة! الرجاء اختيار موظفة أخرى.');
        }

        session([
            'booking.staff_id' => $validated['staff_id'],
            'booking.location' => $validated['location']
        ]);
        session()->save();

        return redirect()->route('customer.bookings.confirm');
    }

    // ================= CONFIRM =================
    public function confirm()
    {
        $booking = session('booking');
        
        if (!$booking) {
            return redirect()->route('customer.bookings.step1')
                ->with('error', 'انتهت الجلسة، الرجاء البدء من جديد');
        }
        
        $staff = User::find($booking['staff_id']);
        $user = auth()->user();
        
        $services = [
            'classic' => 30,
            'wet' => 40,
            'wispy' => 50,
            'volume' => 45,
            'anime' => 55
        ];
        $originalPrice = $services[$booking['service_type']] ?? 30;
        
        $discountPercentage = $user->discount_percentage ?? 0;
        $finalPrice = $originalPrice - ($originalPrice * $discountPercentage / 100);
        
        if ($booking['location'] == 'home') {
            $finalPrice += 10;
        }
        
        $nextDiscountIn = $user->next_discount_booking;
        $completedBookings = $user->completed_bookings_count;
        
        return view('customer.bookings.confirm', compact('booking', 'staff', 'originalPrice', 'finalPrice', 'discountPercentage', 'nextDiscountIn', 'completedBookings'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = session('booking');
        
        if (!$data) {
            return redirect()->route('customer.bookings.step1')
                ->with('error', 'انتهت الجلسة');
        }
        
        $user = auth()->user();
        
        $services = [
            'classic' => 30,
            'wet' => 40,
            'wispy' => 50,
            'volume' => 45,
            'anime' => 55
        ];
        
        $originalPrice = $services[$data['service_type']] ?? 0;
        
        $discount = 0;
        if ($user->canApplyDiscount()) {
            $discount = 15;
            $user->applyDiscount();
        }
        
        $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
        
        if ($data['location'] == 'home') {
            $finalPrice += 10;
        }
        
        $bookingDate = $data['booking_date'];
        $status = $bookingDate < today() ? 'completed' : 'confirmed';
        
        $booking = Booking::create([
            'user_id' => $user->id,
            'staff_id' => $data['staff_id'],
            'service_type' => $data['service_type'],
            'eye_shape' => $data['eye_shape'] ?? null,
            'lash_duration' => $data['lash_duration'] ?? null,
            'style_preference' => $data['style_preference'] ?? null,
            'booking_date' => $bookingDate,
            'booking_time' => $data['booking_time'],
            'location' => $data['location'],
            'price' => $finalPrice,
            'status' => $status
        ]);
        
        $user->increment('total_bookings');
        $user->increment('loyalty_points', 10);
        
        session()->forget('booking');
        
        return redirect()->route('customer.bookings.show', $booking->id)
            ->with('success', 'تم حجز موعدك بنجاح! 🎉');
    }

    // ================= BOOKINGS MANAGEMENT =================
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->paginate(10);
        
        return view('customer.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with('staff', 'review')->findOrFail($id);

        if ($booking->user_id != auth()->id()) {
            abort(403);
        }

        return view('customer.bookings.show', compact('booking'));
    }

    // ✅ دالة الإلغاء المعدلة (ترجع النقاط)
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $user = auth()->user();

        if ($booking->user_id != $user->id) {
            abort(403);
        }
        
        if ($booking->booking_date < today()) {
            return back()->with('error', '❌ لا يمكن إلغاء حجز بتاريخ مضى');
        }
        
        // ✅ إذا كان الحجز مؤكد، نرجع النقاط
        if ($booking->status == 'confirmed') {
            // تحديد عدد النقاط حسب نوع الخدمة
            if ($booking->service_type == 'removal') {
                $user->decrement('loyalty_points', 5);
            } else {
                $user->decrement('loyalty_points', 10);
            }
        }
        
        $booking->update(['status' => 'cancelled']);
        
        return redirect()->route('customer.bookings.index')
            ->with('success', '✅ تم إلغاء الحجز واسترداد نقاطك بنجاح');
    }

    // ================= QUEUE SYSTEM =================
    public function showQueueForm()
    {
        $services = [
            'classic' => 'Classic Set (30 د.أ)',
            'wet' => 'Wet Set (40 د.أ)',
            'wispy' => 'Wispy Set (50 د.أ)',
            'volume' => 'Volume Set (45 د.أ)',
            'anime' => 'Anime Set (55 د.أ)',
            'removal' => 'إزالة الرموش (5 د.أ)'
        ];
        return view('customer.queue.join', compact('services'));
    }

    public function joinQueue(Request $request)
    {
        $request->validate([
            'service_type' => 'required',
            'preferred_date' => 'required|date|after:today',
        ]);
        
        $existingQueue = Queue::where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->first();
        
        if ($existingQueue) {
            return back()->with('error', '⚠️ أنتِ بالفعل في طابور الانتظار! رقمك الحالي: ' . $existingQueue->position);
        }
        
        $lastQueue = Queue::where('status', 'waiting')->orderBy('position', 'desc')->first();
        $newPosition = $lastQueue ? $lastQueue->position + 1 : 1;
        
        $queue = Queue::create([
            'user_id' => auth()->id(),
            'service_type' => $request->service_type,
            'preferred_date' => $request->preferred_date,
            'position' => $newPosition,
            'status' => 'waiting',
        ]);
        
        return redirect()->route('customer.queue.status', $queue->id)
            ->with('success', '✅ تم إضافتك إلى طابور الانتظار! رقمك: ' . $newPosition);
    }

    public function queueStatus($id)
    {
        $queue = Queue::with('staff')->findOrFail($id);
        
        if ($queue->user_id != auth()->id()) {
            abort(403);
        }
        
        $this->updateQueuePositions();
        
        $queue = Queue::with('staff')->findOrFail($id);
        
        $peopleAhead = Queue::where('status', 'waiting')
            ->where('position', '<', $queue->position)
            ->count();
        
        if ($peopleAhead == 0 && $queue->position == 1) {
            $queue->update(['status' => 'notified']);
        }
        
        $estimatedWait = $peopleAhead * 30;
        $yourTurnSoon = $peopleAhead <= 2;
        
        return view('customer.queue.waiting', compact('queue', 'peopleAhead', 'estimatedWait', 'yourTurnSoon'));
    }

    public function cancelQueue($id)
    {
        $queue = Queue::findOrFail($id);
        
        if ($queue->user_id != auth()->id()) {
            abort(403);
        }
        
        $queue->update(['status' => 'cancelled']);
        
        $this->updateQueuePositions();
        
        return redirect()->route('customer.dashboard')
            ->with('success', 'تم إلغاء طلب الانتظار');
    }

    // ================= HELPER FUNCTIONS =================
    
    private function getAvailableDates()
    {
        $dates = [];
        $totalStaff = User::where('role', 'staff')->count();
        if ($totalStaff == 0) $totalStaff = 1;
        
        for ($i = 1; $i <= 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            
            $bookingsCount = Booking::where('booking_date', $date)
                ->where('status', 'confirmed')
                ->count();
            
            $availableSlots = ($totalStaff * 3) - $bookingsCount;
            
            if ($availableSlots > 0) {
                $dates[] = $date;
            }
            
            if (count($dates) >= 7) {
                break;
            }
        }
        
        return $dates;
    }

    private function getAvailableTimes()
    {
        return ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
    }

    private function getSuggestedDates()
    {
        $suggested = [];
        
        $totalStaff = User::where('role', 'staff')->count();
        if ($totalStaff == 0) $totalStaff = 1;
        
        for ($i = 1; $i <= 30; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            
            $bookingsCount = Booking::where('booking_date', $date)
                ->where('status', 'confirmed')
                ->count();
            
            $maxPerDay = $totalStaff * 3;
            $availableSlots = $maxPerDay - $bookingsCount;
            
            if ($availableSlots > 0 && count($suggested) < 3) {
                $suggested[] = [
                    'date' => $date,
                    'day' => $this->getDayNameArabic($date),
                    'available_slots' => $availableSlots,
                ];
            }
        }
        
        return $suggested;
    }

    private function getDayNameArabic($date)
    {
        $day = Carbon::parse($date)->format('D');
        $days = [
            'Sat' => 'السبت', 'Sun' => 'الأحد', 'Mon' => 'الإثنين',
            'Tue' => 'الثلاثاء', 'Wed' => 'الأربعاء', 'Thu' => 'الخميس', 'Fri' => 'الجمعة'
        ];
        return $days[$day] ?? $day;
    }

    private function updateQueuePositions()
    {
        $queues = Queue::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $position = 1;
        foreach ($queues as $queue) {
            $queue->update(['position' => $position]);
            $position++;
        }
    }
}