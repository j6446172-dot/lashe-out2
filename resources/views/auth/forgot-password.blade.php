@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card rounded-3xl p-8 w-full max-w-md shadow-xl">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: rgba(200, 162, 122, 0.15);">
                <i class="fas fa-envelope text-3xl" style="color: var(--luxury-brown);"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--luxury-dark);">نسيت كلمة المرور؟</h2>
            <p class="text-gray-500 mt-2">لا تقلقي! سنرسل لك رابط إعادة التعيين</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-right">
                <i class="fas fa-check-circle ml-2 text-green-600"></i>
                <span>✅ تم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني</span>
            </div>
            <div class="text-center mt-2 mb-4">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-envelope ml-1"></i> إذا لم يصلك البريد، تحقق من الرسائل غير المرغوب فيها (Spam)
                </p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-right">
                <i class="fas fa-exclamation-circle ml-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="resetForm">
            @csrf

            <div class="mb-6">
                <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                    <i class="fas fa-envelope ml-1" style="color: var(--luxury-brown);"></i> البريد الإلكتروني
                </label>
                <input type="email" name="email" id="email" 
                    class="w-full px-4 py-3 rounded-xl text-right bg-white/80 border border-[#C8A27A]/30 focus:border-[#C8A27A] focus:outline-none focus:ring-2 focus:ring-[#C8A27A]/30"
                    placeholder="example@mail.com" required>
                <p class="text-gray-400 text-xs mt-2 text-right">سيتم إرسال رابط إعادة تعيين كلمة المرور إلى هذا البريد الإلكتروني</p>
            </div>

            <button type="submit" id="submitBtn" class="btn-luxury w-full text-white font-bold py-3 rounded-xl transition shadow-md shadow-[#C8A27A]/30 flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane text-xl"></i>
                <span id="btnText">إرسال رابط التعيين</span>
                <span id="btnLoader" class="hidden">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
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

<script>
    document.getElementById('resetForm')?.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        if (!email) {
            e.preventDefault();
            return;
        }
        
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        
        btnText.classList.add('hidden');
        btnLoader.classList.remove('hidden');
        btn.disabled = true;
        btn.style.opacity = '0.7';
    });
</script>
@endsection