<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use App\Models\User;

class FinanceController extends Controller
{
    /**
     * 💰 الصفحة المالية
     * 
     * تعرض:
     * - الإيرادات والمصاريف وصافي الربح
     * - رسوم بيانية للإيرادات الشهرية وتوزيع المصاريف
     * - مقارنة بالشهر الماضي
     * - إمكانية إضافة خصم أو مكافأة للموظفات
     */
    public function finance()
    {
        // 💰 إيرادات الشهر الحالي
        $monthlyRevenue = Booking::whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->where('status', 'confirmed')
            ->sum('price') ?? 0;
            
        // 🧮 حساب المصاريف
        $staffCount = User::where('role', 'staff')->count();
        $salaries = $staffCount * 350;
        $materials = $monthlyRevenue * 0.06;
        $rent = 60;
        $netProfit = $monthlyRevenue - $salaries - $materials - $rent;
        
        // 📊 بيانات الرسم البياني للإيرادات
        $chartMonths = []; 
        $chartRevenue = [];
        for ($i = 5; $i >= 0; $i--) { 
            $d = now()->subMonths($i); 
            $chartMonths[] = $d->format('M'); 
            $chartRevenue[] = Booking::whereMonth('booking_date', $d->month)
                ->whereYear('booking_date', $d->year)
                ->where('status', 'confirmed')
                ->sum('price') ?? 0; 
        }
        
        // 📊 مقارنة بالشهر الماضي
        $lastMonthRevenue = Booking::whereMonth('booking_date', now()->subMonth()->month)
            ->whereYear('booking_date', now()->subMonth()->year)
            ->where('status', 'confirmed')
            ->sum('price') ?? 0;
        $thisMonthBookings = Booking::whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)->count();
        $lastMonthBookings = Booking::whereMonth('booking_date', now()->subMonth()->month)
            ->whereYear('booking_date', now()->subMonth()->year)->count();
        $lastMonthNetProfit = $lastMonthRevenue - ($staffCount * 350) - ($lastMonthRevenue * 0.06) - $rent;
        
        // 👩‍💼 قائمة الموظفات (للخصم والمكافأة)
        $staffList = User::where('role', 'staff')->get()
            ->map(fn($x) => ['id' => $x->id, 'name' => $x->name])->toArray();
            
        return view('owner.finance', compact(
            'monthlyRevenue', 'salaries', 'materials', 'rent', 'netProfit', 
            'chartMonths', 'chartRevenue', 'lastMonthRevenue', 'thisMonthBookings', 
            'lastMonthBookings', 'lastMonthNetProfit', 'staffList'
        ));
    }

    /**
     * 🔒 التحقق من كلمة المرور المالية
     * 
     * يتم استدعاؤها من النافذة المنبثقة في الداشبورد
     */
    public function verifyFinanceLogin(Request $request)
    {
        $user = auth()->user();
        
        // إذا ما ضبط كلمة مرور مالية بعد
        if (!$user->finance_password) {
            return redirect()->route('owner.dashboard')
                ->with('finance_error', 'لم يتم ضبط كلمة المرور المالية بعد');
        }
        
        // التحقق من صحة كلمة المرور
        if (Hash::check($request->password, $user->finance_password)) {
            return redirect()->route('owner.finance');
        }
        
        return redirect()->route('owner.dashboard')
            ->with('finance_error', 'كلمة المرور غير صحيحة');
    }

    /**
     * ➖➕ حفظ خصم أو مكافأة لموظفة
     */
    public function saveFinance(Request $request)
    {
        $s = User::find($request->staff_id);
        if (!$s) return back()->with('error', 'الموظفة غير موجودة');
        
        // إضافة الخصم أو المكافأة
        if ($request->type == 'deduction') { 
            $s->deduction = ($s->deduction ?? 0) + $request->amount; 
        } else { 
            $s->bonus = ($s->bonus ?? 0) + $request->amount; 
        }
        $s->save();
        
        return redirect()->route('owner.finance')->with('success', 'تم الحفظ بنجاح ✅');
    }

    /**
     * 🔒 تحديث كلمة المرور المالية
     */
    public function updateFinancePassword(Request $request)
    {
        $request->validate(['finance_password' => 'required|min:4|confirmed']);
        auth()->user()->update(['finance_password' => Hash::make($request->finance_password)]);
        return back()->with('success', 'تم حفظ كلمة المرور المالية بنجاح ✅');
    }
}