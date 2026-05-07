<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" dir="rtl">
            {{ __('جدول المواعيد اليومي 💄') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-8">
                    <h3 style="font-size: 1.4rem; font-weight: 700; color: #6366f1;">مرحباً بكِ في فريق العمل ✨</h3>
                    <p style="color: #666; margin-top: 10px;">
                        هنا ستجدين قائمة الحجوزات الخاصة بكِ لهذا اليوم. تأكدي من تحديث حالة الحجز بعد الانتهاء.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>