<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="glass-card rounded-3xl p-8 w-full max-w-md shadow-xl">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: rgba(200, 162, 122, 0.15);">
                    <i class="fas fa-envelope-open-text text-3xl" style="color: var(--luxury-brown);"></i>
                </div>
                <h2 class="text-2xl font-bold" style="color: var(--luxury-dark);">تأكيد البريد الإلكتروني ✨</h2>
                <p class="text-gray-500 mt-4 leading-relaxed">
                    شكراً لانضمامك إلينا! قبل البدء، هل يمكنكِ تأكيد بريدكِ الإلكتروني من خلال الضغط على الرابط الذي أرسلناه لكِ للتو؟ 
                    إذا لم يصلكِ البريد، سنرسل لكِ واحداً آخر بكل سرور.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-right flex items-center justify-end gap-2">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    <span>تم إرسال رابط تأكيد جديد إلى بريدكِ الإلكتروني.</span>
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-luxury w-full text-white font-bold py-3 rounded-xl transition shadow-md shadow-[#C8A27A]/30">
                        <i class="fas fa-paper-plane ml-2"></i>
                        إعادة إرسال رابط التأكيد 📧
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center transition hover:opacity-70 py-2" style="color: var(--luxury-dark);">
                        <i class="fas fa-sign-out-alt ml-1"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>