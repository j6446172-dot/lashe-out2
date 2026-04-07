<x-guest-layout>
    <div style="text-align: center; margin-bottom: 25px; font-family: 'Tajawal', sans-serif;">
        <h2 style="color: #ec4899; font-weight: 800; font-size: 1.8rem; margin-bottom: 5px;">انضمي إلينا ✨</h2>
        <p style="color: #888; font-size: 0.9rem;">أنشئي حسابكِ للتمتع بكافة خدماتنا</p>
    </div>

    <form method="POST" action="{{ route('register') }}" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">الاسم الكامل</label>
            <input id="name" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">رقم الهاتف</label>
            <input id="phone" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="text" name="phone" :value="old('phone')" placeholder="07XXXXXXXX" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">البريد الإلكتروني</label>
            <input id="email" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div style="margin-bottom: 15px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">كلمة المرور</label>
            <input id="password" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div style="margin-bottom: 20px;">
            <label style="color: #666; font-size: 0.9rem; display: block; margin-bottom: 5px; text-align: right;">تأكيد كلمة المرور</label>
            <input id="password_confirmation" class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring-pink-200 shadow-sm text-right" type="password" name="password_confirmation" required />
        </div>

        <button type="submit" class="w-full py-4 rounded-2xl font-bold text-white shadow-lg" style="background: linear-gradient(to left, #ec4899, #f472b6); border: none; cursor: pointer;">
            إنشاء حساب 💖
        </button>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('login') }}" style="color: #ec4899; text-decoration: none; font-size: 0.9rem; font-weight: 700;">
                لديكِ حساب بالفعل؟ تسجيل دخول
            </a>
        </div>
    </form>
</x-guest-layout>