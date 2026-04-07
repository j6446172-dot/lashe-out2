<x-guest-layout>
    <div style="text-align: center; margin-bottom: 30px; font-family: 'Tajawal', sans-serif;">
        <h2 style="color: #ec4899; font-weight: 800; font-size: 1.8rem; margin-bottom: 8px;">
            تسجيل الدخول ✨
        </h2>
        <p style="color: #888; font-size: 0.95rem;">
            مرحباً بكِ مجدداً في Lashe Out
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" dir="rtl" style="font-family: 'Tajawal', sans-serif;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label for="email" style="color: #666; font-size: 0.9rem; font-weight: 600; display: block; margin-bottom: 8px; text-align: right;">
                البريد الإلكتروني
            </label>
            <input id="email" 
                   class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 shadow-sm transition duration-200 text-right" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   placeholder="example@mail.com"
                   required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
        </div>

        <div style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-direction: row-reverse;">
                <label for="password" style="color: #666; font-size: 0.9rem; font-weight: 600;">
                    كلمة المرور
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: #f472b6; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                        نسيتِ كلمة المرور؟
                    </a>
                @endif
            </div>
            <input id="password" 
                   class="block w-full px-4 py-3 rounded-2xl border-gray-100 bg-gray-50 focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 shadow-sm transition duration-200 text-right" 
                   type="password" 
                   name="password" 
                   placeholder="••••••••"
                   required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
        </div>

        <div class="block mt-4 text-right">
            <label for="remember_me" class="inline-flex items-center flex-row-reverse">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 text-pink-500 shadow-sm focus:ring-pink-500" name="remember">
                <span class="mr-2 text-sm text-gray-600">تذكريني</span>
            </label>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px; align-items: center; margin-top: 35px;">
            <button type="submit" 
                    class="w-full py-4 rounded-2xl font-bold text-white shadow-lg transition duration-300 transform hover:scale-[1.02]" 
                    style="background: linear-gradient(to left, #ec4899, #f472b6); border: none; cursor: pointer; font-size: 1.1rem;">
                دخول 💖
            </button>
            
            <p style="color: #666; font-size: 0.9rem;">
                ليس لديكِ حساب؟ 
                <a href="{{ route('register') }}" style="color: #ec4899; text-decoration: none; font-weight: 800;">
                    أنشئي حساب جديد
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>