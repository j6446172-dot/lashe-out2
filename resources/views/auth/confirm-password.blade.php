@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card rounded-3xl p-8 w-full max-w-md shadow-xl">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: rgba(200, 162, 122, 0.15);">
                <i class="fas fa-key text-3xl" style="color: var(--luxury-brown);"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--luxury-dark);">نسيت كلمة المرور؟</h2>
            <p class="text-gray-500 mt-2">لا تقلقي! سنرسل لك رابط إعادة التعيين</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-right flex items-center justify-end gap-2">
                <i class="fab fa-whatsapp text-green-600 text-xl"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-right">
                <i class="fas fa-exclamation-circle ml-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                    <i class="fab fa-whatsapp ml-1" style="color: var(--luxury-brown);"></i> رقم واتساب
                </label>
                <input type="tel" name="phone" 
                    class="w-full px-4 py-3 rounded-xl text-right bg-white/80 border border-[#C8A27A]/30 focus:border-[#C8A27A] focus:outline-none focus:ring-2 focus:ring-[#C8A27A]/30"
                    placeholder="079XXXXXXXX" required>
                <p class="text-gray-400 text-xs mt-2 text-right">سنرسل رابط إعادة التعيين على هذا الرقم</p>
            </div>

            <button type="submit" class="btn-luxury w-full text-white font-bold py-3 rounded-xl transition shadow-md shadow-[#C8A27A]/30 flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-xl"></i>
                إرسال رابط إعادة التعيين
            </button>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm transition hover:opacity-70 inline-flex items-center gap-1" style="color: var(--luxury-brown);">
                    <i class="fas fa-arrow-right"></i>
                    تذكرت كلمة المرور؟ تسجيل دخول
                </a>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t" style="border-color: rgba(200, 162, 122, 0.2);">
            <a href="/" class="text-sm transition hover:opacity-70" style="color: var(--luxury-dark);">
                <i class="fas fa-arrow-right ml-1"></i> العودة إلى الصفحة الرئيسية
            </a>
        </div>

    </div>
</div>
@endsection