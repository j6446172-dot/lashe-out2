<x-guest-layout>
    <div style="text-align: center; margin-bottom: 25px; font-family: 'Tajawal', sans-serif;" dir="rtl">
        <h2 style="color: #ec4899; font-weight: 800; font-size: 1.7rem; margin-bottom: 15px;">تأكيد البريد الإلكتروني ✨</h2>
        <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
            شكراً لانضمامك إلينا! قبل البدء، هل يمكنكِ تأكيد بريدكِ الإلكتروني من خلال الضغط على الرابط الذي أرسلناه لكِ للتو؟ إذا لم يصلكِ البريد، سنرسل لكِ واحداً آخر بكل سرور.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="margin-bottom: 20px; font-size: 0.85rem; color: #10b981; text-align: center; font-weight: bold;">
            تم إرسال رابط تأكيد جديد إلى بريدكِ الإلكتروني.
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 15px; align-items: center; margin-top: 25px; font-family: 'Tajawal', sans-serif;">
        <form method="POST" action="{{ route('verification.send') }}" style="width: 100%;">
            @csrf
            <button type="submit" class="w-full py-4 rounded-2xl font-bold text-white shadow-lg" style="background: linear-gradient(to left, #ec4899, #f472b6); border: none; cursor: pointer;">
                إعادة إرسال رابط التأكيد 📧
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="color: #888; text-decoration: underline; font-size: 0.9rem; background: none; border: none; cursor: pointer;">
                تسجيل الخروج
            </button>
        </form>
    </div>
</x-guest-layout>