<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // ================= Dashboard =================
    public function dashboard()
    {
        // 🔥 تحديث حجوزات إزالة الرموش المنتهية تلقائياً
        $this->autoCompleteRemovalBookings();
        
        $user = auth()->user();
        
        $completedBookings = $user->bookings()
            ->where('status', 'completed')
            ->count();
        
        $totalBookings = $user->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        
        $upcomingBookings = $user->bookings()
            ->where('booking_date', '>=', today())
            ->whereIn('status', ['confirmed', 'completed'])
            ->orderBy('booking_date')
            ->take(3)
            ->get();

        $loyaltyPoints = $user->points;
        
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

        $lashDuration = $request->has('lash_duration') ? $request->lash_duration : 'monthly';

        session([
            'booking.eye_shape' => $request->eye_shape,
            'booking.lash_duration' => $lashDuration,
        ]);

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
            'booking.booking_time' => $validated['booking_time'],
            'booking.latitude' => $request->input('latitude', session('booking.latitude')),
            'booking.longitude' => $request->input('longitude', session('booking.longitude')),
            'booking.address_text' => $request->input('address_text', session('booking.address_text')),
            'booking.building_number' => $request->input('building_number', session('booking.building_number')),
            'booking.apartment' => $request->input('apartment', session('booking.apartment')),
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
            'location' => 'required|in:salon,home',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address_text' => 'nullable|string|max:500',
            'building_number' => 'nullable|string|max:50',   
            'apartment' => 'nullable|string|max:50',         
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
            'booking.location' => $validated['location'],
            'booking.latitude' => $validated['latitude'],
            'booking.longitude' => $validated['longitude'],
            'booking.address_text' => $validated['address_text'],
            'booking.building_number' => $validated['building_number'],   
            'booking.apartment' => $validated['apartment'],               
            'booking.save_address' => true,
        ]);
        session()->save();

        if ($validated['location'] == 'home' && $request->has('save_address')) {
            $user = auth()->user();
            
            if ($validated['latitude'] && $validated['longitude'] && $validated['address_text']) {
                $user->default_latitude = $validated['latitude'];
                $user->default_longitude = $validated['longitude'];
                $user->default_address = $validated['address_text'];
            }
            
            if (!empty($validated['building_number'])) {
                $user->default_building_number = $validated['building_number'];
            }
            
            if (!empty($validated['apartment'])) {
                $user->default_apartment = $validated['apartment'];
            }
            
            $user->save();
        }

        return redirect()->route('customer.bookings.confirm');
    }

    // ================= CONFIRM - صفحة التأكيد =================
   public function confirm()
{
    $booking = session('booking');
    
    if (!$booking) {
        return redirect()->route('customer.bookings.step1')
            ->with('error', 'انتهت الجلسة، الرجاء البدء من جديد');
    }
    
    $staff = User::find($booking['staff_id']);
    $user = auth()->user();
    
    $services = ['classic' => 30, 'wet' => 40, 'wispy' => 50, 'volume' => 45, 'anime' => 55];
    $originalPrice = $services[$booking['service_type']] ?? 30;
    
    $lashDuration = $booking['lash_duration'] ?? $user->last_lash_duration ?? 'monthly';
    switch ($lashDuration) {
        case 'one-time':
            $priceAfterDuration = $originalPrice * 0.5;
            break;
        case 'weekly':
            $priceAfterDuration = $originalPrice * 0.65;
            break;
        default:
            $priceAfterDuration = $originalPrice;
            break;
    }
    
    $basePrice = $priceAfterDuration;
    
    if ($booking['location'] == 'home') {
        $basePrice += 10;
    }
    
    $hasDiscount = $user->isEligibleForDiscount();
    
    
    $discountAmount = $hasDiscount ? $user->getDiscountAmount($basePrice) : 0;
    $finalPriceAfterDiscount = $basePrice - $discountAmount;
    if ($finalPriceAfterDiscount < 0) $finalPriceAfterDiscount = 0;
    
    return view('customer.bookings.confirm', compact('booking', 'staff', 'basePrice', 'hasDiscount', 'discountAmount', 'finalPriceAfterDiscount'));
}
    // ================= تحديث حجوزات إزالة الرموش المنتهية تلقائياً =================
    public function autoCompleteRemovalBookings()
    {
        $expiredRemovals = Booking::where('status', 'confirmed')
            ->where('service_type', 'removal')
            ->where(function($query) {
                $query->where('booking_date', '<', today())
                      ->orWhere(function($q) {
                          $q->where('booking_date', today())
                            ->where('booking_time', '<', now()->format('H:i'));
                      });
            })
            ->get();
        
        foreach ($expiredRemovals as $booking) {
            $booking->status = 'completed';
            $booking->completed_at = now();
            $booking->save();
            
            // إضافة 5 نقاط تلقائياً لإزالة الرموش
            $booking->user->addPoints(5);
        }
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

    $services = ['classic' => 30, 'wet' => 40, 'wispy' => 50, 'volume' => 45, 'anime' => 55];
    $originalPrice = $services[$data['service_type']] ?? 0;

    $lashDuration = $data['lash_duration'] ?? $user->last_lash_duration ?? 'monthly';
    switch ($lashDuration) {
        case 'one-time':
            $priceAfterDuration = $originalPrice * 0.5;
            break;
        case 'weekly':
            $priceAfterDuration = $originalPrice * 0.65;
            break;
        default:
            $priceAfterDuration = $originalPrice;
            break;
    }

    $basePrice = $priceAfterDuration;
    
    if ($data['location'] == 'home') {
        $basePrice += 10;
    }

    //  حساب الخصم
    $discountAmount = 0;
    $useDiscount = $request->input('use_discount', 0);
    
    if ($useDiscount && $user->isEligibleForDiscount()) {
        $discountAmount = $user->applyDiscount($basePrice); //  تمرير $basePrice
    }

    $finalPrice = $basePrice - $discountAmount;
    
    if ($finalPrice < 0) $finalPrice = 0;

    $booking = Booking::create([
        'user_id' => $user->id,
        'staff_id' => $data['staff_id'],
        'service_type' => $data['service_type'],
        'eye_shape' => $data['eye_shape'] ?? null,
        'lash_duration' => $lashDuration,
        'booking_date' => $data['booking_date'],
        'booking_time' => $data['booking_time'],
        'location' => $data['location'],
        'price' => $finalPrice,
        'status' => 'confirmed',
        'latitude' => $data['latitude'] ?? null,
        'longitude' => $data['longitude'] ?? null,
        'address_text' => $data['address_text'] ?? null,
        'building_number' => $data['building_number'] ?? null,
        'apartment' => $data['apartment'] ?? null,
    ]);

    Mail::to($user->email)->send(new BookingConfirmedMail($booking, $user));

    if ($data['save_address'] ?? false) {
        $user->update([
            'default_latitude' => $data['latitude'] ?? null,
            'default_longitude' => $data['longitude'] ?? null,
            'default_address' => $data['address_text'] ?? null,
            'default_building_number' => $data['building_number'] ?? null,
            'default_apartment' => $data['apartment'] ?? null,
            'last_lash_duration' => $lashDuration,
        ]);
    }

    $user->increment('total_bookings');
    session()->forget('booking');

    return redirect()->route('customer.bookings.show', $booking->id)
        ->with('success', 'تم حجز موعدك بنجاح! 🎉');
}

// ================= إكمال الحجز (للموظفة/الأدمن) =================
public function completeBooking($id)
{
    $booking = Booking::findOrFail($id);
    
    if (!in_array(auth()->user()->role, ['admin', 'staff', 'owner'])) {
        abort(403);
    }
    
    if ($booking->status === 'completed') {
        return back()->with('error', 'هذا الحجز مكتمل بالفعل');
    }
    
    if ($booking->status !== 'confirmed') {
        return back()->with('error', 'لا يمكن إكمال حجز غير مؤكد');
    }
    
    if ($booking->service_type == 'removal') {
        return back()->with('error', '❌ حجوزات إزالة الرموش تكتمل تلقائياً');
    }
    
    $booking->status = 'completed';
    $booking->completed_at = now();
    $booking->save();
    
    //  ا إضافة نقاط بقوة
    $userId = $booking->user_id;
    $pointsToAdd = 10;
    
    // 1. نبحث عن السجل
    $check = DB::table('loyalty_points')->where('user_id', $userId)->first();
    
    if ($check) {
        // 2. إذا موجود نزود النقاط
        DB::table('loyalty_points')->where('user_id', $userId)->update([
            'points' => $check->points + $pointsToAdd,
            'updated_at' => now()
        ]);
    } else {
        // 3. إذا مش موجود ننشئ سجل جديد
        DB::table('loyalty_points')->insert([
            'user_id' => $userId,
            'points' => $pointsToAdd,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    // 4. نتأكد من الرصيد الجديد (للتأكد فقط)
    $newPoints = DB::table('loyalty_points')->where('user_id', $userId)->value('points');
    
    return back()->with('success', "✅ تم إكمال الحجز وأضيفت {$pointsToAdd} نقاط (الرصيد الآن: {$newPoints})");
}

    // ================= BOOKINGS MANAGEMENT =================
    public function index()
    {
        // 🔥 تحديث حجوزات إزالة الرموش المنتهية تلقائياً
        $this->autoCompleteRemovalBookings();
        
        $user = auth()->user();
        
        $activeBookings = $user->bookings()
            ->where('status', 'confirmed')
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();
        
        $pastBookings = $user->bookings()
            ->where('status', 'completed')
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();
        
        $cancelledBookings = $user->bookings()
            ->where('status', 'cancelled')
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();
        
        return view('customer.bookings.index', compact('activeBookings', 'pastBookings', 'cancelledBookings'));
    }

    public function show($id)
    {
        $booking = Booking::with('staff', 'review')->findOrFail($id);

        if ($booking->user_id != auth()->id() && !in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403);
        }

        return view('customer.bookings.show', compact('booking'));
    }

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
        
        $booking->update(['status' => 'cancelled']);
        
        return redirect()->route('customer.bookings.index')
            ->with('success', '✅ تم إلغاء الحجز بنجاح');
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
            $date = now()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            if ($date->dayOfWeek == 5) {
                continue;
            }
            
            $bookingsCount = Booking::where('booking_date', $dateString)
                ->where('status', 'confirmed')
                ->count();
            
            $availableSlots = ($totalStaff * 3) - $bookingsCount;
            
            if ($availableSlots > 0) {
                $dates[] = $dateString;
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
         \Cache::forget('suggested_dates');
        $suggested = [];
        
        $totalStaff = User::where('role', 'staff')->count();
        if ($totalStaff == 0) $totalStaff = 1;
        
        for ($i = 1; $i <= 30; $i++) {
            $date = now()->addDays($i);
            $dateString = $date->format('Y-m-d');
            
            if ($date->dayOfWeek == 5) {
                continue;
            }
            
            $bookingsCount = Booking::where('booking_date', $dateString)
                ->where('status', 'confirmed')
                ->count();
            
            $maxPerDay = $totalStaff * 3;
            $availableSlots = $maxPerDay - $bookingsCount;
            
            if ($availableSlots > 0 && count($suggested) < 3) {
                $suggested[] = [
                    'date' => $dateString,
                    'day' => $this->getDayNameArabic($dateString),
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