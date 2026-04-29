<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;

class OwnerController extends Controller
{
    // ========== الداشبورد ==========
    public function dashboard()
    {
        $this->checkHighCancelRate();

        $chartMonths = []; $chartRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i); $chartMonths[] = $date->format('M');
            $chartRevenue[] = Booking::whereMonth('booking_date', $date->month)->whereYear('booking_date', $date->year)->where('status', 'confirmed')->sum('price') ?? 0;
        }

        $customerGrowthMonths = []; $customerGrowthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i); $customerGrowthMonths[] = $date->format('M');
            $customerGrowthData[] = User::where('role', 'customer')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
        }

        $totalCustomers = User::where('role', 'customer')->count();
        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->where('status', 'confirmed')->sum('price') ?? 0;
        $staffCount = User::where('role', 'staff')->count();
        $salaries = $staffCount * 350; $materials = $monthlyRevenue * 0.06; $rent = 60;
        $netProfit = $monthlyRevenue - $salaries - $materials - $rent;
        $returnRate = $this->calculateReturnRate();
        $averageRating = Review::avg('rating') ?? 0;
        $todayBookings = Booking::whereDate('booking_date', today())->count();
        $todayBookingsList = Booking::with(['user', 'staff'])->whereDate('booking_date', today())->orderBy('booking_time')->take(5)->get();
        $staffPerformance = $this->getStaffPerformance();

        return view('owner.dashboard', compact(
            'totalCustomers', 'monthlyRevenue', 'netProfit', 'returnRate',
            'averageRating', 'todayBookings', 'todayBookingsList', 'staffPerformance',
            'salaries', 'materials', 'rent', 'chartMonths', 'chartRevenue',
            'customerGrowthMonths', 'customerGrowthData'
        ));
    }

    public function updateSalonSchedule(Request $request)
    {
        foreach ($request->status as $index => $status) {
            \DB::table('salon_schedule')->updateOrInsert(
                ['day_of_week' => $index],
                ['is_open' => $status == 'open' ? 1 : 0, 'start_time' => $request->start[$index] ?? '10:00', 'end_time' => $request->end[$index] ?? '18:00', 'updated_at' => now()]
            );
            
            if ($status == 'closed') {
                \DB::table('staff_schedule')->where('day_of_week', $index)->update([
                    'status' => 'dayoff', 'start_time' => null, 'end_time' => null, 'updated_at' => now()
                ]);
            }
            
            if ($status == 'open') {
                \DB::table('staff_schedule')->where('day_of_week', $index)->update([
                    'status' => 'active', 'start_time' => $request->start[$index] ?? '10:00', 'end_time' => $request->end[$index] ?? '18:00', 'updated_at' => now()
                ]);
            }
        }
        return back()->with('success', 'تم حفظ دوام الصالون ✅');
    }

    public function getStaffSchedule($id)
    {
        $day = request('day', 0);
        $schedule = \DB::table('staff_schedule')->where('staff_id', $id)->where('day_of_week', $day)->first();
        return response()->json($schedule ?? ['status' => 'active', 'start_time' => '10:00', 'end_time' => '18:00']);
    }

    public function saveStaffSchedule(Request $request)
    {
        \DB::table('staff_schedule')->updateOrInsert(
            ['staff_id' => $request->staff_id, 'day_of_week' => $request->day_of_week],
            ['status' => $request->status, 'start_time' => $request->start_time, 'end_time' => $request->end_time, 'updated_at' => now()]
        );
        return response()->json(['success' => true]);
    }

    public function checkHighCancelRate()
    {
        $staffWithHighCancel = User::where('role', 'staff')->get()->filter(function($s) {
            $total = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->count();
            $cancelled = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count();
            return $total > 0 && ($cancelled / $total) * 100 > 15;
        });

        foreach ($staffWithHighCancel as $staff) {
            $exists = \DB::table('notifications')
                ->where('type', 'high_cancel')
                ->where('owner_id', 1)
                ->whereDate('created_at', today())
                ->exists();
            
            if (!$exists) {
                \DB::table('notifications')->insert([
                    'owner_id' => 1,
                    'type' => 'high_cancel',
                    'title' => '⚠️ نسبة إلغاء مرتفعة',
                    'message' => "{$staff->name} لديها نسبة إلغاء مرتفعة هذا الشهر",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function bookings()
    {
        $query = Booking::query();
        if (request('search')) { $query->whereHas('user', function($q) { $q->where('name', 'like', '%' . request('search') . '%'); }); }
        if (request('status')) { $query->where('status', request('status')); }
        if (request('staff')) { $query->where('staff_id', request('staff')); }
        
        $totalBookings = $query->count();
        $confirmedCount = (clone $query)->where('status', 'confirmed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $cancelledCount = (clone $query)->where('status', 'cancelled')->count();
        $tomorrowCount = (clone $query)->whereDate('booking_date', today()->addDay())->count();
        $peakTime = (clone $query)->selectRaw('booking_time, COUNT(*) as count')->groupBy('booking_time')->orderByDesc('count')->first();
        $peakTime = $peakTime ? $peakTime->booking_time : '—';
        $allBookings = (clone $query)->with(['user', 'staff'])->latest()->paginate(20);
        $staffList = User::where('role', 'staff')->get();
        return view('owner.bookings', compact('totalBookings', 'confirmedCount', 'pendingCount', 'cancelledCount', 'tomorrowCount', 'peakTime', 'allBookings', 'staffList'));
    }

    public function bookingDetail($id) { $b = Booking::with(['user', 'staff'])->find($id); if (!$b) return response()->json(['error' => 'غير موجود']); return response()->json(['id' => $b->id, 'user' => ['name' => $b->user->name ?? '', 'email' => $b->user->email ?? '', 'phone' => $b->user->phone ?? ''], 'service' => $b->service, 'price' => $b->price, 'staff' => ['name' => $b->staff->name ?? ''], 'staff_rating' => Review::where('staff_id', $b->staff_id)->avg('rating') ?? 0, 'booking_date' => $b->booking_date, 'booking_time' => $b->booking_time, 'status' => $b->status, 'notes' => $b->notes ?? '']); }

    public function staffDetail($id) { $s = User::find($id); if (!$s || $s->role !== 'staff') return response()->json(['error' => 'غير موجود']); $t = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->count(); $c = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count(); $r = Booking::where('staff_id', $id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->sum('price') ?? 0; return response()->json(['name' => $s->name, 'total_bookings' => $t, 'cancel_rate' => $t > 0 ? round(($c / $t) * 100, 1) : 0, 'rating' => number_format(Review::where('staff_id', $id)->avg('rating') ?? 0, 1), 'revenue' => number_format($r), 'base_salary' => $s->salary ?? 350, 'deduction' => $s->deduction ?? 0, 'bonus' => $s->bonus ?? 0, 'net_salary' => ($s->salary ?? 350) + ($s->bonus ?? 0) - ($s->deduction ?? 0)]); }

    public function addStaff(Request $request) { User::create(['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'password' => Hash::make('password'), 'role' => 'staff', 'salary' => $request->salary ?? 350, 'specialty' => $request->specialty ?? '']); return response()->json(['success' => true]); }
    public function updateStaff(Request $request, $id) { User::findOrFail($id)->update(['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'specialty' => $request->specialty, 'salary' => $request->salary]); return response()->json(['success' => true]); }
    public function deleteStaff($id) { User::findOrFail($id)->delete(); return response()->json(['success' => true]); }
    public function staffSchedule($id) { $s = User::findOrFail($id); return view('owner.staff-schedule', compact('s')); }

    public function staff()
    {
        $staffMembers = User::where('role', 'staff')->get();
        $totalStaff = $staffMembers->count();
        $activeStaff = $staffMembers->where('status', 'active')->count();
        $onLeaveStaff = $staffMembers->where('status', 'leave')->count();
        $avgRating = Review::whereIn('staff_id', $staffMembers->pluck('id'))->avg('rating') ?? 0;
        $staffList = [];
        foreach ($staffMembers as $staff) {
            $total = Booking::where('staff_id', $staff->id)->whereMonth('booking_date', now()->month)->count();
            $cancelled = Booking::where('staff_id', $staff->id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count();
            $rating = Review::where('staff_id', $staff->id)->avg('rating') ?? 0;
            $staffList[] = ['id' => $staff->id, 'name' => $staff->name, 'specialty' => $staff->specialty ?? '—', 'total_bookings' => $total, 'cancel_rate' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0, 'rating' => $rating, 'base_salary' => $staff->salary ?? 350, 'deduction' => $staff->deduction ?? 0, 'bonus' => $staff->bonus ?? 0, 'net_salary' => ($staff->salary ?? 350) + ($staff->bonus ?? 0) - ($staff->deduction ?? 0)];
        }
        return view('owner.staff', compact('totalStaff', 'activeStaff', 'onLeaveStaff', 'avgRating', 'staffList'));
    }

    public function verifyFinanceLogin(Request $request) 
    { 
        $user = auth()->user(); 
        if (!$user->finance_password) {
            return redirect()->route('owner.dashboard')->with('finance_error', 'لم يتم ضبط كلمة المرور المالية بعد');
        }
        if (Hash::check($request->password, $user->finance_password)) {
            return redirect()->route('owner.finance');
        }
        return redirect()->route('owner.dashboard')->with('finance_error', 'كلمة المرور غير صحيحة');
    }

    public function finance()
    {
        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->where('status', 'confirmed')->sum('price') ?? 0;
        $staffCount = User::where('role', 'staff')->count();
        $salaries = $staffCount * 350;
        $materials = $monthlyRevenue * 0.06;
        $rent = 60;
        $netProfit = $monthlyRevenue - $salaries - $materials - $rent;
        $chartMonths = []; $chartRevenue = [];
        for ($i = 5; $i >= 0; $i--) { $d = now()->subMonths($i); $chartMonths[] = $d->format('M'); $chartRevenue[] = Booking::whereMonth('booking_date', $d->month)->whereYear('booking_date', $d->year)->where('status', 'confirmed')->sum('price') ?? 0; }
        $lastMonthRevenue = Booking::whereMonth('booking_date', now()->subMonth()->month)->whereYear('booking_date', now()->subMonth()->year)->where('status', 'confirmed')->sum('price') ?? 0;
        $thisMonthBookings = Booking::whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->count();
        $lastMonthBookings = Booking::whereMonth('booking_date', now()->subMonth()->month)->whereYear('booking_date', now()->subMonth()->year)->count();
        $lastMonthNetProfit = $lastMonthRevenue - ($staffCount * 350) - ($lastMonthRevenue * 0.06) - $rent;
        $staffList = User::where('role', 'staff')->get()->map(fn($x) => ['id' => $x->id, 'name' => $x->name])->toArray();
        $transactions = [];
        return view('owner.finance', compact('monthlyRevenue', 'salaries', 'materials', 'rent', 'netProfit', 'chartMonths', 'chartRevenue', 'lastMonthRevenue', 'thisMonthBookings', 'lastMonthBookings', 'lastMonthNetProfit', 'staffList', 'transactions'));
    }

    public function saveFinance(Request $request)
    {
        $s = User::find($request->staff_id);
        if (!$s) return back()->with('error', 'الموظفة غير موجودة');
        if ($request->type == 'deduction') { $s->deduction = ($s->deduction ?? 0) + $request->amount; }
        else { $s->bonus = ($s->bonus ?? 0) + $request->amount; }
        $s->save();
        return redirect()->route('owner.finance')->with('success', 'تم الحفظ بنجاح ✅');
    }

    private function calculateReturnRate() { $t = User::where('role', 'customer')->count(); if ($t == 0) return 0; $r = 0; foreach (User::where('role', 'customer')->get() as $c) { if (Booking::where('user_id', $c->id)->where('status', 'confirmed')->count() >= 2) $r++; } return round(($r / $t) * 100); }
    private function getStaffPerformance() { $p = []; foreach (User::where('role', 'staff')->get() as $s) { $t = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->count(); $co = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->count(); $ca = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'cancelled')->count(); $rv = Booking::where('staff_id', $s->id)->whereMonth('booking_date', now()->month)->where('status', 'confirmed')->sum('price') ?? 0; $p[] = ['name' => $s->name, 'total_bookings' => $t, 'completed' => $co, 'cancelled' => $ca, 'cancel_rate' => $t > 0 ? round(($ca / $t) * 100, 1) : 0, 'rating' => Review::where('staff_id', $s->id)->avg('rating') ?? 0, 'revenue' => $rv]; } return $p; }
    public function updateFinancePassword(Request $request) { $request->validate(['finance_password' => 'required|min:4|confirmed']); auth()->user()->update(['finance_password' => Hash::make($request->finance_password)]); return back()->with('success', 'تم حفظ كلمة المرور المالية بنجاح ✅'); }

    public function customers()
    {
        $customers = User::where('role', 'customer')->get();
        $totalCustomers = $customers->count();
        $newCustomers = $customers->where('created_at', '>=', now()->startOfMonth())->count();
        $returningCustomers = $customers->filter(fn($c) => Booking::where('user_id', $c->id)->count() >= 2)->count();
        $totalLoyaltyPoints = $customers->sum('loyalty_points');
        $avgCustomerRating = Review::avg('rating') ?? 0;
        $customersList = [];
        foreach ($customers as $c) {
            $b = Booking::where('user_id', $c->id);
            $customersList[] = ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone, 'first_booking' => $b->min('booking_date'), 'total_bookings' => $b->count(), 'total_spent' => $b->where('status', 'confirmed')->sum('price'), 'loyalty_points' => $c->loyalty_points ?? 0, 'rating' => Review::where('user_id', $c->id)->avg('rating') ?? 0, 'is_vip' => ($c->loyalty_points ?? 0) >= 100];
        }
        return view('owner.customers', compact('totalCustomers', 'newCustomers', 'returningCustomers', 'totalLoyaltyPoints', 'avgCustomerRating', 'customersList'));
    }

    public function customerDetail($id) { $c = User::find($id); if (!$c || $c->role !== 'customer') return response()->json(['error' => 'غير موجود']); $b = Booking::where('user_id', $id); return response()->json(['id' => $c->id, 'name' => $c->name, 'email' => $c->email, 'phone' => $c->phone, 'total_bookings' => $b->count(), 'total_spent' => number_format($b->where('status', 'confirmed')->sum('price')), 'loyalty_points' => $c->loyalty_points ?? 0, 'rating' => number_format(Review::where('user_id', $id)->avg('rating') ?? 0, 1), 'recent_bookings' => $b->with('staff')->latest()->take(5)->get()->map(fn($x) => ['date' => $x->booking_date, 'service' => $x->service, 'staff' => $x->staff->name ?? '—'])]); }
    public function schedule() { $staffMembers = User::where('role', 'staff')->get(); return view('owner.schedule', compact('staffMembers')); }

    public function reviews()
    {
        $query = Review::query();
        if (request('rating')) { $query->where('rating', request('rating')); }
        if (request('staff')) { $query->where('staff_id', request('staff')); }
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();
        $maxRating = Review::max('rating') ?? 0;
        $todayReviews = Review::whereDate('created_at', today())->count();
        $reviews = $query->with(['user', 'staff', 'booking'])->latest()->paginate(20);
        $staffList = User::where('role', 'staff')->get();
        return view('owner.reviews', compact('avgRating', 'totalReviews', 'maxRating', 'todayReviews', 'reviews', 'staffList'));
    }
}