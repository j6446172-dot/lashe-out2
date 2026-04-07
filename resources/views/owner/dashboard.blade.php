<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight" dir="rtl">
            {{ __('لوحة تحكم الإدارة - Lashe Out ✨') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border-t-4 border-pink-400">
                <div class="p-8 text-gray-900">
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #ec4899; margin-bottom: 15px;">أهلاً بكِ أيتها المديرة 👑</h3>
                    <p style="color: #666; line-height: 1.6;">
                        من هنا يمكنكِ متابعة أداء الستوديو، إدارة الموظفات، والاطلاع على المواعيد المحجوزة.
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                        <div style="background: #fff5f7; padding: 20px; border-radius: 20px; text-align: center; border: 1px solid #fce7f3;">
                            <span style="font-size: 1.2rem; font-weight: bold; color: #db2777;">إدارة الموظفات</span>
                        </div>
                        <div style="background: #fff5f7; padding: 20px; border-radius: 20px; text-align: center; border: 1px solid #fce7f3;">
                            <span style="font-size: 1.2rem; font-weight: bold; color: #db2777;">التقارير المالية</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>