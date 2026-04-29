<?php
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\QueueController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\RemovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// صفحة تسجيل دخول المالك
Route::get('/owner/login', function () {
    return view('auth.login');
})->name('owner.login');


Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\OwnerController::class, 'dashboard'])->name('dashboard');
});


// ========== الصفحات العامة ==========
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/booking', 'auth.register')->name('booking.form');
// ========== مسارات المصادقة (Auth) ==========
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ========== مسارات الخروج ==========
Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ========== مسارات تغيير كلمة المرور ==========
Route::middleware(['auth'])->group(function () {
    Route::put('/password/update', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    })->name('password.update');
});

// ========== مسارات الملف الشخصي ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========== مسارات العميل ==========
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
    
    // الحجوزات - الخطوات
    Route::get('/bookings/create/step1', [BookingController::class, 'step1Style'])->name('bookings.step1');
    Route::post('/bookings/create/step1', [BookingController::class, 'postStep1'])->name('bookings.step1.post');
    Route::get('/bookings/create/step2', [BookingController::class, 'step2Service'])->name('bookings.step2');
    Route::post('/bookings/create/step2', [BookingController::class, 'postStep2'])->name('bookings.step2.post');
    Route::get('/bookings/create/step3', [BookingController::class, 'step3DateTime'])->name('bookings.step3');
    Route::post('/bookings/create/step3', [BookingController::class, 'postStep3'])->name('bookings.step3.post');
    Route::get('/bookings/create/step4', [BookingController::class, 'step4Staff'])->name('bookings.step4');
    Route::post('/bookings/create/step4', [BookingController::class, 'postStep4'])->name('bookings.step4.post');
    Route::get('/bookings/create/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    
    // إدارة الحجوزات
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // الطابور
    Route::get('/queue/join', [BookingController::class, 'showQueueForm'])->name('queue.form');
    Route::post('/queue/join', [BookingController::class, 'joinQueue'])->name('queue.join');
    Route::get('/queue/{queue}/status', [BookingController::class, 'queueStatus'])->name('queue.status');
    Route::delete('/queue/{queue}', [BookingController::class, 'cancelQueue'])->name('queue.cancel');
    
    // التقييمات
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create/{booking}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{booking}', [ReviewController::class, 'store'])->name('reviews.store');
    
    // إزالة الرموش
    Route::get('/removal', [RemovalController::class, 'step1'])->name('removal.step1');
    Route::post('/removal/store', [RemovalController::class, 'store'])->name('removal.store');
});

// ========== API الأوقات المتاحة (AJAX) ==========
Route::middleware(['auth'])->get('/customer/get-available-times', function (Request $request) {
    $date = $request->date;
    
    if (!$date) {
        return response()->json(['error' => 'التاريخ مطلوب'], 400);
    }
    
    $totalStaff = \App\Models\User::where('role', 'staff')->count();
    if ($totalStaff == 0) $totalStaff = 1;
    
    $allTimes = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
    $times = [];
    
    foreach ($allTimes as $time) {
        $bookingsCount = \App\Models\Booking::where('booking_date', $date)
            ->where('booking_time', $time)
            ->where('status', 'confirmed')
            ->count();
        
        $isAvailable = $bookingsCount < $totalStaff;
        $isPeak = $bookingsCount >= ($totalStaff * 0.7);
        
        $hour = (int)explode(':', $time)[0];
        if ($hour < 12) {
            $period = 'صباحاً';
        } elseif ($hour == 12) {
            $period = 'ظهراً';
        } else {
            $period = 'مساءً';
        }
        
        $times[] = [
            'time' => $time,
            'available' => $isAvailable,
            'peak' => $isPeak,
            'period' => $period
        ];
    }
    
    return response()->json($times);
})->name('customer.get-available-times');

// ========== API التحديث التلقائي للتقييمات (Live Update) ==========
Route::middleware(['auth'])->get('/staff/ratings', function () {
    $staff = App\Models\User::where('role', 'staff')->get();
    $data = [];
    foreach ($staff as $staffMember) {
        $avgRating = $staffMember->staffReviews()->avg('rating');
        $ratingCount = $staffMember->staffReviews()->count();
        
        if ($avgRating) {
            $stars = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= floor($avgRating)) {
                    $stars .= '<i class="fas fa-star text-xs"></i>';
                } elseif ($i - 0.5 <= $avgRating) {
                    $stars .= '<i class="fas fa-star-half-alt text-xs"></i>';
                } else {
                    $stars .= '<i class="far fa-star text-xs text-gray-300"></i>';
                }
            }
            
            $ratingHtml = '
                <div class="flex items-center justify-end gap-2 mt-1">
                    <div class="flex text-yellow-400">' . $stars . '</div>
                    <span class="text-xs font-bold text-gray-700">' . number_format($avgRating, 1) . '</span>
                    <span class="text-gray-400 text-xs">·</span>
                    <span class="text-xs text-gray-500">(' . $ratingCount . ' ' . ($ratingCount == 1 ? 'تقييم' : 'تقييمات') . ')</span>
                </div>
            ';
        } else {
            $ratingHtml = '<p class="text-xs text-gray-400 mt-1">✨ لا توجد تقييمات بعد - كوني أول من يقيم</p>';
        }
        
        $data[] = [
            'id' => $staffMember->id,
            'rating_html' => $ratingHtml
        ];
    }
    return response()->json($data);
})->name('staff.ratings');

// ========== مسارات المالك (Owner) ==========
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\BookingoController;
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\Owner\FinanceController;
use App\Http\Controllers\Owner\CustomerController;
use App\Http\Controllers\Owner\ReviewoController;
use App\Http\Controllers\Owner\ScheduleController;

Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [BookingoController::class, 'bookings'])->name('bookings');
Route::get('/booking-detail/{id}', [BookingoController::class, 'bookingDetail'])->name('booking.detail');
    Route::get('/staff', [StaffController::class, 'staff'])->name('staff');
    Route::get('/staff-detail/{id}', [StaffController::class, 'staffDetail'])->name('staff.detail');
    Route::post('/staff/add', [StaffController::class, 'addStaff']);
    Route::post('/staff/update/{id}', [StaffController::class, 'updateStaff']);
    Route::post('/staff/delete/{id}', [StaffController::class, 'deleteStaff']);
    Route::get('/staff/schedule/{id}', [StaffController::class, 'staffSchedule']);
    Route::get('/finance', [FinanceController::class, 'finance'])->name('finance');
    Route::post('/verify-finance-login', [FinanceController::class, 'verifyFinanceLogin'])->name('verify-finance-login');
    Route::post('/update-finance-password', [FinanceController::class, 'updateFinancePassword'])->name('update-finance-password');
    Route::post('/save-finance', [FinanceController::class, 'saveFinance'])->name('saveFinance');
    Route::get('/customers', [CustomerController::class, 'customers'])->name('customers');
    Route::get('/customer-detail/{id}', [CustomerController::class, 'customerDetail']);
    Route::get('/reviews', [ReviewoController::class, 'reviews'])->name('reviews');
    Route::get('/schedule', [ScheduleController::class, 'schedule'])->name('schedule');
    Route::post('/schedule/updateSalon', [ScheduleController::class, 'updateSalonSchedule'])->name('schedule.updateSalon');
    Route::get('/staff-schedule/{id}', [ScheduleController::class, 'getStaffSchedule']);
    Route::post('/staff-schedule/save', [ScheduleController::class, 'saveStaffSchedule']);
});
// ========== مسارات لوحة التحكم العامة ==========
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'customer') {
        return redirect()->route('customer.dashboard');
    } elseif ($user->role === 'staff') {
        return redirect()->route('staff.dashboard');
    } elseif ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }
    return redirect('/');
})->name('dashboard');