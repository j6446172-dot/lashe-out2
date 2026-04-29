@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- بطاقة الترحيب --}}
            <div class="rounded-3xl shadow-xl mb-8 overflow-hidden" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                <div class="px-8 py-10 text-right" style="color: #F3EDE6;">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-black mb-2">👤 ملفي الشخصي</h1>
                            <p style="color: rgba(243, 237, 230, 0.8);">تحديث بياناتك ومعلوماتك الشخصية</p>
                        </div>
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center" style="background: rgba(255, 255, 255, 0.2);">
                            <i class="fas fa-user-edit text-4xl" style="color: #F3EDE6;"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- رسالة النجاح --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl text-right flex items-center gap-3" style="background: rgba(16, 185, 129, 0.1); border-right: 3px solid #10b981;">
                    <i class="fas fa-check-circle text-xl" style="color: #10b981;"></i>
                    <span style="color: #2B1E1A;">{{ session('success') }}</span>
                </div>
            @endif

            {{-- رسالة الخطأ العامة --}}
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl text-right flex items-center gap-3" style="background: rgba(220, 38, 38, 0.1); border-right: 3px solid #dc2626;">
                    <i class="fas fa-exclamation-circle text-xl" style="color: #dc2626;"></i>
                    <span style="color: #991b1b;">{{ session('error') }}</span>
                </div>
            @endif

            {{-- رسائل الخطأ من Validation --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl" style="background: rgba(220, 38, 38, 0.1); border-right: 3px solid #dc2626;">
                    <ul class="list-disc list-inside text-right" style="color: #991b1b;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- نموذج تعديل الملف الشخصي --}}
            <div class="rounded-2xl overflow-hidden" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                <div class="border-b px-6 py-4" style="border-color: rgba(176, 141, 87, 0.1);">
                    <h2 class="text-xl font-bold flex items-center gap-2" style="color: #2B1E1A;">
                        <i class="fas fa-info-circle" style="color: #B08D57;"></i> معلومات الحساب
                    </h2>
                </div>
                
                <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-5">
                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-user ml-1"></i> الاسم الكامل
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-envelope ml-1"></i> البريد الإلكتروني
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-phone ml-1"></i> رقم الهاتف
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">
                                <i class="fas fa-gem ml-1"></i> نقاط الولاء
                            </label>
                            <div class="w-full px-4 py-3 rounded-xl text-right" style="background: rgba(176, 141, 87, 0.05); border: 1px solid rgba(176, 141, 87, 0.15); color: #7C8574;">
                                {{ $user->loyalty_points ?? 0 }} نقطة
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button type="submit" class="flex-1 font-bold py-3 rounded-xl transition hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-save ml-2"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('customer.dashboard') }}" class="flex-1 text-center font-bold py-3 rounded-xl transition hover:opacity-80" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">
                            <i class="fas fa-arrow-right ml-2"></i> رجوع
                        </a>
                    </div>
                </form>
            </div>

            {{-- قسم تغيير كلمة المرور --}}
            <div class="rounded-2xl overflow-hidden mt-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
                <div class="border-b px-6 py-4" style="border-color: rgba(176, 141, 87, 0.1);">
                    <h2 class="text-xl font-bold flex items-center gap-2" style="color: #2B1E1A;">
                        <i class="fas fa-lock" style="color: #B08D57;"></i> تغيير كلمة المرور
                    </h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" required>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">كلمة المرور الجديدة</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-3 rounded-xl text-right" 
                                   style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);" required>
                        </div>

                        <button type="submit" class="font-bold py-3 px-6 rounded-xl transition hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                            <i class="fas fa-key ml-2"></i> تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>

@if(auth()->user()->role === 'owner')
<div class="rounded-2xl overflow-hidden mt-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.2);">
    <div class="border-b px-6 py-4" style="border-color: rgba(176, 141, 87, 0.1);">
        <h2 class="text-xl font-bold flex items-center gap-2" style="color: #2B1E1A;">
            <i class="fas fa-lock" style="color: #B08D57;"></i> كلمة المرور المالية
        </h2>
    </div>
    <div class="p-6">
        <p class="text-right mb-4" style="color: #7C8574;">تستخدم للوصول إلى التقارير المالية الحساسة</p>
        
        <form method="POST" action="{{ route('owner.update-finance-password') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">كلمة المرور الجديدة</label>
                <input type="password" name="finance_password" required
                       class="w-full px-4 py-3 rounded-xl text-right" 
                       style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);">
            </div>
            <div>
                <label class="block font-bold mb-2 text-right" style="color: #2B1E1A;">تأكيد كلمة المرور</label>
                <input type="password" name="finance_password_confirmation" required
                       class="w-full px-4 py-3 rounded-xl text-right" 
                       style="background: rgba(255, 255, 255, 0.8); border: 1px solid rgba(176, 141, 87, 0.3);">
            </div>
            <button type="submit" class="font-bold py-3 px-6 rounded-xl transition hover:shadow-lg" style="background: #B08D57; color: #F3EDE6;">
                <i class="fas fa-save ml-2"></i> حفظ
            </button>
            
            @if(auth()->user()->finance_password)
                <span style="color: #10b981; margin-right: 10px;">✅ مفعلة</span>
            @else
                <span style="color: #f59e0b; margin-right: 10px;">⚠️ لم تضبط بعد</span>
            @endif
        </form>
    </div>
</div>
@endif
            {{-- قسم حذف الحساب --}}
            <div class="rounded-2xl overflow-hidden mt-6" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(220, 38, 38, 0.2);">
                <div class="border-b px-6 py-4" style="border-color: rgba(220, 38, 38, 0.1); background: rgba(220, 38, 38, 0.05);">
                    <h2 class="text-xl font-bold flex items-center gap-2" style="color: #dc2626;">
                        <i class="fas fa-exclamation-triangle"></i> حذف الحساب
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-right mb-4" style="color: #7C8574;">⚠️ تحذير: بمجرد حذف حسابك، سيتم حذف جميع بياناتك نهائياً ولا يمكن استعادتها.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-bold py-3 px-6 rounded-xl transition hover:shadow-lg" 
                                style="background: #dc2626; color: white;"
                                onclick="return confirm('هل أنت متأكدة من حذف حسابك؟ هذا الإجراء لا يمكن التراجع عنه.')">
                            <i class="fas fa-trash-alt ml-2"></i> حذف الحساب
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection