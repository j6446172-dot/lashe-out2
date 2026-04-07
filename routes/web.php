<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (المسارات العامة)
|--------------------------------------------------------------------------
| هذه المسارات متاحة للجميع قبل تسجيل الدخول.
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (المسارات المحمية)
|--------------------------------------------------------------------------
| جميع هذه المسارات تتطلب تسجيل الدخول أولاً.
*/

Route::middleware('auth')->group(function () {

    /*
    |------------------------------------------------------------
    | 1. مسارات صاحبة الستوديو (Owner Only) ✨
    |------------------------------------------------------------
    */
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner/dashboard', function () {
            return view('owner.dashboard');
        })->name('owner.dashboard');

        // يمكنكِ لاحقاً إضافة مسارات إدارة الموظفات هنا
        // Route::resource('employees', EmployeeController::class);
    });

    /*
    |------------------------------------------------------------
    | 2. مسارات الموظفات (Staff Only) 💄
    |------------------------------------------------------------
    */
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', function () {
            return view('staff.dashboard');
        })->name('staff.dashboard');

        // يمكنكِ لاحقاً إضافة مسارات جدول المواعيد للموظفة هنا
    });

    /*
    |------------------------------------------------------------
    | 3. مسارات الزبائن (Customer Only) 🌸
    |------------------------------------------------------------
    */
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // يمكنكِ لاحقاً إضافة مسارات حجز المواعيد للزبونة هنا
    });

    /*
    |------------------------------------------------------------
    | 4. مسارات البروفايل المشتركة (Common Profile)
    |------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Auth System Routes
|--------------------------------------------------------------------------
| جلب مسارات تسجيل الدخول والتسجيل الافتراضية من Breeze.
*/
require __DIR__.'/auth.php';