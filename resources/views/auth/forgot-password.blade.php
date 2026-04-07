<x-guest-layout>
    <div style="text-align: center; margin-bottom: 25px; font-family: 'Tajawal', sans-serif;">
        <h2 style="color: #ec4899; font-weight: 800; font-size: 1.6rem; margin-bottom: 10px;">استعادة الحساب 🔑</h2>
        <p style="color: #777; font-size: 0.9rem; line-height: 1.6;">
            نسيتِ كلمة المرور؟ لا تقلقي. أخبرينا ببريدك الإلكتروني وسنرسل لكِ رابطاً لتعيين كلمة مرور جديدة.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 8px; text-align: right;">البريد الإلكتروني</label>
            <input id="email" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl font-bold text-white shadow-lg shadow-pink-100" style="background: linear-gradient(to left, #ec4899, #f472b6); border: none; cursor: pointer;">
            إرسال رابط الاستعادة 📧
        </button>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('login') }}" style="color: #888; text-decoration: none; font-size: 0.85rem;">
                العودة لتسجيل الدخول
            </a>
        </div>
    </form>
</x-guest-layout>