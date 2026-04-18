@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="glass-card rounded-3xl p-8 w-full max-w-md shadow-xl">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: rgba(200, 162, 122, 0.15);">
                <i class="fas fa-lock text-3xl" style="color: var(--luxury-brown);"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--luxury-dark);">إعادة تعيين كلمة المرور</h2>
            <p class="text-gray-500 mt-2">أدخل كلمة المرور الجديدة</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-right">
                <i class="fas fa-exclamation-circle ml-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->token }}">

            <div class="mb-4">
                <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                    <i class="fas fa-envelope ml-1" style="color: var(--luxury-brown);"></i> البريد الإلكتروني
                </label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                    class="w-full px-4 py-3 rounded-xl text-right bg-white/80 border border-[#C8A27A]/30 focus:border-[#C8A27A] focus:outline-none focus:ring-2 focus:ring-[#C8A27A]/30"
                    required>
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                    <i class="fas fa-lock ml-1" style="color: var(--luxury-brown);"></i> كلمة المرور الجديدة
                </label>
                <input type="password" name="password" 
                    class="w-full px-4 py-3 rounded-xl text-right bg-white/80 border border-[#C8A27A]/30 focus:border-[#C8A27A] focus:outline-none focus:ring-2 focus:ring-[#C8A27A]/30"
                    placeholder="********" required>
            </div>

            <div class="mb-6">
                <label class="block font-bold mb-2 text-right" style="color: var(--luxury-dark);">
                    <i class="fas fa-lock ml-1" style="color: var(--luxury-brown);"></i> تأكيد كلمة المرور الجديدة
                </label>
                <input type="password" name="password_confirmation" 
                    class="w-full px-4 py-3 rounded-xl text-right bg-white/80 border border-[#C8A27A]/30 focus:border-[#C8A27A] focus:outline-none focus:ring-2 focus:ring-[#C8A27A]/30"
                    placeholder="********" required>
            </div>

            <button type="submit" class="btn-luxury w-full text-white font-bold py-3 rounded-xl transition shadow-md shadow-[#C8A27A]/30">
                <i class="fas fa-save ml-2"></i> تغيير كلمة المرور
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t" style="border-color: rgba(200, 162, 122, 0.2);">
            <a href="{{ route('login') }}" class="text-sm transition hover:opacity-70 inline-flex items-center gap-1" style="color: var(--luxury-brown);">
                <i class="fas fa-arrow-right"></i>
                العودة إلى تسجيل الدخول
            </a>
        </div>

    </div>
</div>
@endsection