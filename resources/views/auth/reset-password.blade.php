<x-guest-layout>
    <div style="text-align: center; margin-bottom: 25px; font-family: 'Tajawal', sans-serif;">
        <h2 style="color: #ec4899; font-weight: 800; font-size: 1.7rem; margin-bottom: 8px;">تحديث كلمة المرور 🔐</h2>
        <p style="color: #888; font-size: 0.9rem;">الرجاء إدخال كلمة المرور الجديدة لحماية حسابكِ</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">البريد الإلكتروني</label>
            <input id="email" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">كلمة المرور الجديدة</label>
            <input id="password" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div style="margin-bottom: 25px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">تأكيد كلمة المرور</label>
            <input id="password_confirmation" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl font-bold text-white shadow-lg shadow-pink-100" style="background: linear-gradient(to left, #ec4899, #f472b6); border: none; cursor: pointer; font-size: 1.1rem;">
            تغيير كلمة المرور 💖
        </button>
    </form>
</x-guest-layout>