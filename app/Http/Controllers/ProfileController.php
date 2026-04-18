<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * تحديث بيانات الملف الشخصي (الاسم، البريد، الهاتف)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('profile.edit')->with('success', 'تم تحديث ملفك الشخصي بنجاح ✨');
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح 🔒');
    }

    /**
     * حذف الحساب بالكامل
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // تسجيل الخروج أولاً
        Auth::logout();
        
        // حذف المستخدم
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم حذف حسابك بنجاح');
    }
}