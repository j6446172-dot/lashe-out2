@auth
    {{-- ========== شريط المالك ========== --}}
    @if(auth()->user()->role === 'owner')
    <nav class="glass-nav sticky top-0 z-50 border-b" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('owner.dashboard') }}" style="font-size: 22px; font-weight: 900; color: #8B6B4A; text-decoration: none;">
                    LASHE OUT 👑
                </a>
                <div style="display: flex; align-items: center; gap: 10px;">
                    {{-- إشعارات المالك --}}
                    <div class="relative" x-data="{ notif: false }">
                        @php 
                            $notifications = \DB::table('notifications')
                                ->where('owner_id', auth()->id())
                                ->latest()
                                ->take(5)
                                ->get();
                            $unreadCount = \DB::table('notifications')
                                ->where('owner_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        
                        <button @click="notif = !notif" @click.away="notif = false" 
                                style="width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(200,162,122,0.1); border: none; cursor: pointer; font-size: 18px; position: relative;">
                            🔔
                            @if($unreadCount > 0)
                                <span style="position: absolute; top: -3px; right: -3px; width: 18px; height: 18px; border-radius: 50%; background: #ef4444; color: white; font-size: 10px; display: flex; align-items: center; justify-content: center;">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        
                        <div x-show="notif" @click.away="notif = false" x-cloak 
                             class="absolute left-0 mt-2 w-72 rounded-2xl shadow-xl z-50 overflow-hidden" 
                             style="background: white; border: 1px solid rgba(200, 162, 122, 0.15);">
                            <div class="px-4 py-3" style="border-bottom: 1px solid rgba(200, 162, 122, 0.1);">
                                <p class="font-bold" style="color: #8B6B4A;">🔔 الإشعارات</p>
                            </div>
                            @forelse($notifications as $n)
                                <div class="px-4 py-3 hover:bg-gray-50 border-b text-sm" style="border-color: rgba(200, 162, 122, 0.1);">
                                    <p class="font-bold" style="color: #2B1E1A;">{{ $n->title }}</p>
                                    <p style="color: #7C8574;">{{ $n->message }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-sm" style="color: #7C8574;">لا توجد إشعارات</div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- قائمة المستخدم للمالك --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 font-bold transition focus:outline-none" style="color: #2B1E1A;">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-sm transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                        
                        <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-2xl shadow-xl z-50" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-t-2xl transition" style="color: #2B1E1A;">
                                <i class="fas fa-user w-5" style="color: #B08D57;"></i>
                                <span>ملفي الشخصي</span>
                            </a>
                            <div class="border-t" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-b-2xl transition w-full text-right" style="color: #2B1E1A;">
                                    <i class="fas fa-sign-out-alt w-5" style="color: #B08D57;"></i>
                                    <span>تسجيل خروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>





 {{-- ========== شريط الموظف (STAFF NAVBAR) ========== --}}
@elseif(auth()->user()->role === 'staff')
<nav class="shadow-md border-b sticky top-0 z-50" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Logo --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('staff.dashboard') }}" class="text-2xl font-black tracking-tighter" style="color: #B08D57;">
                    LASHE OUT 💇‍♀️
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('staff.dashboard') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                    <i class="fas fa-chalkboard-user ml-1"></i> لوحة التحكم
                </a>
                <a href="{{ route('staff.bookings') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                    <i class="fas fa-calendar-check ml-1"></i> الحجوزات
                </a>
                <a href="{{ route('staff.schedule') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                    <i class="fas fa-clock ml-1"></i> جدول دوامي
                </a>
                <a href="{{ route('staff.reviews') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                    <i class="fas fa-star ml-1"></i> تقييماتي
                </a>
            </div>

            {{-- User Menu --}}
            <div class="flex items-center gap-4">
                
                {{-- زر المالية والإجازات --}}
                <div class="relative" x-data="{ financialOpen: false }">
                    <button @click="financialOpen = !financialOpen" @click.away="financialOpen = false" 
                            class="px-3 py-1.5 rounded-lg text-sm flex items-center gap-2 transition hover:bg-[#B08D57] hover:text-white"
                            style="border: 1px solid #B08D57; color: #B08D57; background: rgba(176, 141, 87, 0.05);">
                        <i class="fas fa-coins"></i>
                        <span>المالية والإجازات</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{'rotate-180': financialOpen}"></i>
                    </button>
                    
                    <div x-show="financialOpen" x-cloak class="absolute left-0 mt-2 w-80 rounded-xl shadow-xl z-50 overflow-hidden" 
                         style="background: white; border: 1px solid rgba(176, 141, 87, 0.15);">
                        
                        <div class="p-3" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                            <p class="font-bold text-white text-sm flex items-center gap-2">
                                <i class="fas fa-chart-line"></i> تفاصيل الراتب - {{ now()->format('F Y') }}
                            </p>
                        </div>
                        
                        <div class="p-3 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">💰 الراتب الأساسي</span>
                                <span class="font-bold" style="color: #2B1E1A;">{{ number_format($staffSalary['base_salary'] ?? 350) }} د.أ</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">🏷️ الخصم</span>
                                <span class="font-bold text-red-600">- {{ number_format($staffSalary['deduction'] ?? 0) }} د.أ</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">🎁 المكافأة</span>
                                <span class="font-bold text-green-600">+ {{ number_format($staffSalary['bonus'] ?? 0) }} د.أ</span>
                            </div>
                            <div class="border-t border-dashed" style="border-color: rgba(176, 141, 87, 0.2);"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold" style="color: #2B1E1A;">💵 الراتب الصافي</span>
                                <span class="text-xl font-bold" style="color: #B08D57;">{{ number_format($staffSalary['net_salary'] ?? 350) }} د.أ</span>
                            </div>
                        </div>
                        
                        <div class="border-t" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                        
                        <button onclick="openLeaveRequest()" class="w-full p-3 text-right flex items-center gap-2 hover:bg-gray-50 transition" style="color: #2B1E1A;">
                            <i class="fas fa-calendar-alt text-[#B08D57] w-5"></i>
                            <span>📅 طلب إجازة</span>
                            <i class="fas fa-arrow-left text-xs mr-auto text-gray-400"></i>
                        </button>
                        
                        <button onclick="showLeaveHistory()" class="w-full p-3 text-right flex items-center gap-2 hover:bg-gray-50 transition border-t" style="color: #2B1E1A; border-color: rgba(176, 141, 87, 0.1);">
                            <i class="fas fa-history text-[#B08D57] w-5"></i>
                            <span>📋 سجل الإجازات السابقة</span>
                            <i class="fas fa-arrow-left text-xs mr-auto text-gray-400"></i>
                        </button>
                        
                        <button onclick="showSalaryHistory()" class="w-full p-3 text-right flex items-center gap-2 hover:bg-gray-50 transition" style="color: #2B1E1A;">
                            <i class="fas fa-chart-line text-[#B08D57] w-5"></i>
                            <span>💰 سجل الرواتب الشهرية</span>
                            <i class="fas fa-arrow-left text-xs mr-auto text-gray-400"></i>
                        </button>
                    </div>
                </div>

                {{-- زر شات مع المالك --}}
                <button onclick="openChatWindow()" class="px-3 py-1.5 rounded-lg text-sm flex items-center gap-2 transition hover:bg-[#B08D57] hover:text-white relative" style="border: 1px solid #B08D57; color: #B08D57; background: rgba(176, 141, 87, 0.05);">
                    <i class="fas fa-comment-dots"></i>
                    <span>شات مع المالك</span>
                    <span id="chat-unread-badge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                </button>

                {{-- قائمة المستخدم --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 font-bold transition focus:outline-none" style="color: #2B1E1A;">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm transition-transform" :class="{'rotate-180': open}"></i>
                    </button>
                    
                    <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-2xl shadow-xl z-50" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-t-2xl transition" style="color: #2B1E1A;">
                            <i class="fas fa-user w-5" style="color: #B08D57;"></i>
                            <span>ملفي الشخصي</span>
                        </a>
                        <div class="border-t" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-b-2xl transition w-full text-right" style="color: #2B1E1A;">
                                <i class="fas fa-sign-out-alt w-5" style="color: #B08D57;"></i>
                                <span>تسجيل خروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="md:hidden">
                <button id="mobile-menu-button" class="focus:outline-none" style="color: #7C8574;">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden py-4 px-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(176, 141, 87, 0.1);">
        <div class="flex flex-col gap-3">
            <a href="{{ route('staff.dashboard') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">لوحة التحكم</a>
            <a href="{{ route('staff.bookings') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">الحجوزات</a>
            <a href="{{ route('staff.schedule') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">جدول دوامي</a>
            <a href="{{ route('staff.reviews') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">تقييماتي</a>
            
            <button onclick="openLeaveRequest()" class="font-bold py-2 transition hover:opacity-70 text-right" style="color: #B08D57;">📅 طلب إجازة</button>
            <button onclick="showLeaveHistory()" class="font-bold py-2 transition hover:opacity-70 text-right" style="color: #B08D57;">📋 سجل الإجازات</button>
            <button onclick="showSalaryHistory()" class="font-bold py-2 transition hover:opacity-70 text-right" style="color: #B08D57;">💰 سجل الرواتب</button>
            <button onclick="openChatWindow()" class="font-bold py-2 transition hover:opacity-70 text-right" style="color: #B08D57;">💬 شات مع المالك</button>
            
            <a href="{{ route('profile.edit') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">ملفي الشخصي</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-right font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">تسجيل خروج</button>
            </form>
        </div>
    </div>
</nav>

{{-- نافذة الشات --}}
<div id="chatPopup" class="fixed bottom-4 left-4 w-96 bg-white rounded-2xl shadow-2xl z-[100] hidden transition-all duration-300" style="border: 1px solid rgba(176, 141, 87, 0.3);">
    <div class="px-4 py-3 rounded-t-2xl flex justify-between items-center" style="background: #B08D57;">
        <div class="flex items-center gap-2">
            <i class="fas fa-comment-dots text-white"></i>
            <span class="font-bold text-white">شات مع المالك</span>
        </div>
        <button onclick="closeChatWindow()" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-3" style="background: #F9F7F4;">
        <div class="text-center text-gray-400 text-sm py-10"><i class="fas fa-comments text-4xl mb-2 block"></i><p>ابدأ المحادثة مع المالك</p></div>
    </div>
    <div class="p-3 border-t" style="border-color: rgba(176, 141, 87, 0.2); background: white;">
        <form id="popupChatForm" class="flex gap-2">
            @csrf
            <input type="text" id="popupMessageInput" class="flex-1 rounded-xl px-4 py-2 border focus:outline-none focus:border-[#B08D57] text-sm" style="border-color: rgba(176, 141, 87, 0.3);" placeholder="اكتب رسالتك...">
            <button type="submit" class="px-4 py-2 rounded-xl text-white transition hover:opacity-90" style="background: #B08D57;"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

{{-- نافذة طلب إجازة --}}
<div id="leaveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[200]">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden shadow-2xl" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.3);">
        <div class="px-4 py-3" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2"><i class="fas fa-calendar-alt text-white"></i><span class="font-bold text-white">طلب إجازة</span></div>
                <button onclick="closeLeaveModal()" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
        </div>
        
        <form id="leaveRequestForm" class="p-4">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">نوع الإجازة</label>
                    <select name="leave_type" required class="w-full rounded-xl px-4 py-2 border focus:outline-none focus:border-[#B08D57]" style="border-color: rgba(176, 141, 87, 0.3);">
                        <option value="">اختر نوع الإجازة</option>
                        <option value="مرضية">🤒 إجازة مرضية</option>
                        <option value="عارضة">⚡ إجازة عارضة</option>
                        <option value="سنوية">🌴 إجازة سنوية</option>
                        <option value="بدون راتب">💰 إجازة بدون راتب</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">نظام الإجازة</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="duration_type" value="days" checked onclick="toggleDurationType()"> <span>📅 أيام</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="duration_type" value="hours" onclick="toggleDurationType()"> <span>⏰ ساعات</span></label>
                    </div>
                </div>
                
                <div id="daysSection">
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">من تاريخ</label><input type="date" name="start_date" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" min="{{ date('Y-m-d') }}"></div>
                        <div><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">إلى تاريخ</label><input type="date" name="end_date" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);"></div>
                    </div>
                </div>
                
                <div id="hoursSection" style="display:none;">
                    <div><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">التاريخ</label><input type="date" name="hours_date" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" min="{{ date('Y-m-d') }}"></div>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <div><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">من الساعة</label><input type="time" name="start_time" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" step="60"></div>
                        <div><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">إلى الساعة</label><input type="time" name="end_time" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" step="60"></div>
                    </div>
                    <div class="mt-2"><label class="block text-sm font-bold mb-1" style="color: #2B1E1A;">عدد الساعات</label><input type="number" name="hours" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" placeholder="مثال: 4" min="1" max="12"></div>
                </div>
                
                <div><textarea name="reason" rows="2" class="w-full rounded-xl px-4 py-2 border" style="border-color: rgba(176, 141, 87, 0.3);" placeholder="السبب (اختياري)"></textarea></div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeLeaveModal()" class="flex-1 py-2 rounded-xl transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57;">إلغاء</button>
                    <button type="submit" class="flex-1 py-2 rounded-xl text-white transition hover:opacity-90" style="background: #B08D57;">إرسال الطلب</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- نافذة سجل الإجازات --}}
<div id="leaveHistoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[200]">
    <div class="rounded-2xl w-full max-w-3xl mx-4 overflow-hidden shadow-2xl" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.3); max-height: 80vh;">
        <div class="px-4 py-3" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2"><i class="fas fa-history text-white"></i><span class="font-bold text-white">سجل الإجازات السابقة</span></div>
                <button onclick="closeLeaveHistoryModal()" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div id="leaveHistoryContent" class="p-4 overflow-y-auto" style="max-height: calc(80vh - 60px);"><div class="text-center py-8 text-gray-500">جاري التحميل...</div></div>
    </div>
</div>

{{-- نافذة سجل الرواتب --}}
<div id="salaryHistoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[200]">
    <div class="rounded-2xl w-full max-w-3xl mx-4 overflow-hidden shadow-2xl" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.3); max-height: 80vh;">
        <div class="px-4 py-3" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2"><i class="fas fa-chart-line text-white"></i><span class="font-bold text-white">سجل الرواتب الشهرية</span></div>
                <button onclick="closeSalaryHistoryModal()" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div id="salaryHistoryContent" class="p-4 overflow-y-auto" style="max-height: calc(80vh - 60px);"><div class="text-center py-8 text-gray-500">جاري التحميل...</div></div>
    </div>
</div>

<script>
    // ========== دوال الشات ==========
    let chatInterval = null, lastMessageId = 0;
    function openChatWindow() { const p=document.getElementById('chatPopup'); p.classList.remove('hidden'); p.classList.add('show'); loadChatMessages(); if(chatInterval) clearInterval(chatInterval); chatInterval=setInterval(loadChatMessages,3000); fetch('{{ route("chat.mark-read") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}}); const b=document.getElementById('chat-unread-badge'); if(b) b.classList.add('hidden'); }
    function closeChatWindow() { const p=document.getElementById('chatPopup'); p.classList.add('hidden'); p.classList.remove('show'); if(chatInterval){ clearInterval(chatInterval); chatInterval=null; } }
    async function loadChatMessages(){ try{ const r=await fetch('{{ route("chat.messages") }}'); const msgs=await r.json(); const c=document.getElementById('chatMessages'); if(msgs.length>0){ let nid=msgs[msgs.length-1].id; if(nid>lastMessageId){ c.innerHTML=''; msgs.forEach(m=>addMessageToPopup(m)); lastMessageId=nid; c.scrollTop=c.scrollHeight; } } else if(c.innerHTML===''||c.innerHTML.includes('ابدأ المحادثة')) c.innerHTML='<div class="text-center text-gray-400 text-sm py-10"><i class="fas fa-comments text-4xl mb-2 block"></i><p>ابدأ المحادثة مع المالك</p></div>'; } catch(e){ console.error(e); } }
    function addMessageToPopup(m){ const c=document.getElementById('chatMessages'); const isMine=m.from_user_id=={{ auth()->id() }}; if(c.innerHTML.includes('ابدأ المحادثة')) c.innerHTML=''; const d=document.createElement('div'); if(isMine){ d.className='flex justify-end mb-2'; d.innerHTML=`<div class="max-w-[80%] bg-[#B08D57] text-white rounded-2xl rounded-br-none px-3 py-2 shadow"><p class="text-sm">${escapeHtml(m.message)}</p><p class="text-[10px] text-white/60 mt-1">${formatTime(m.created_at)}</p></div>`; } else { d.className='flex justify-start mb-2'; d.innerHTML=`<div class="max-w-[80%] bg-white rounded-2xl rounded-bl-none px-3 py-2 shadow" style="border-right:2px solid #B08D57;"><div class="flex items-center gap-1 mb-1"><i class="fas fa-crown text-[#B08D57] text-[10px]"></i><span class="text-[10px] font-bold" style="color:#B08D57;">المالك</span></div><p class="text-sm text-gray-700">${escapeHtml(m.message)}</p><p class="text-[10px] text-gray-400 mt-1">${formatTime(m.created_at)}</p></div>`; } c.appendChild(d); }
    document.getElementById('popupChatForm')?.addEventListener('submit',async(e)=>{ e.preventDefault(); const i=document.getElementById('popupMessageInput'); const msg=i.value.trim(); if(!msg) return; const btn=e.target.querySelector('button[type="submit"]'); btn.disabled=true; try{ const r=await fetch('{{ route("chat.send") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({message:msg})}); const d=await r.json(); if(d.success){ i.value=''; await loadChatMessages(); } } catch(e){ console.error(e); } finally{ btn.disabled=false; i.focus(); } });
    
    // ========== دوال الإجازة ==========
    function toggleDurationType(){
        const daysSec=document.getElementById('daysSection'), hoursSec=document.getElementById('hoursSection');
        const dur=document.querySelector('input[name="duration_type"]:checked');
        if(!dur) return;
        if(dur.value==='days'){
            daysSec.style.display='block'; hoursSec.style.display='none';
        } else {
            daysSec.style.display='none'; hoursSec.style.display='block';
        }
    }
    function openLeaveRequest(){ document.getElementById('leaveRequestForm')?.reset(); const r=document.querySelector('input[name="duration_type"][value="days"]'); if(r) r.checked=true; toggleDurationType(); document.getElementById('leaveModal').classList.remove('hidden'); document.getElementById('leaveModal').classList.add('flex'); }
    function closeLeaveModal(){ document.getElementById('leaveModal').classList.add('hidden'); document.getElementById('leaveModal').classList.remove('flex'); }
    document.getElementById('leaveRequestForm')?.addEventListener('submit',async(e)=>{ e.preventDefault(); const form=e.target; const durationType=document.querySelector('input[name="duration_type"]:checked').value; let formData={ leave_type:form.querySelector('select[name="leave_type"]').value, duration_type:durationType, reason:form.querySelector('textarea[name="reason"]').value||'' }; if(durationType==='days'){ formData.start_date=form.querySelector('input[name="start_date"]').value; formData.end_date=form.querySelector('input[name="end_date"]').value; } else { formData.start_date=form.querySelector('input[name="hours_date"]').value; formData.end_date=formData.start_date; formData.start_time=form.querySelector('input[name="start_time"]').value; formData.end_time=form.querySelector('input[name="end_time"]').value; formData.hours=form.querySelector('input[name="hours"]').value; } if(!formData.leave_type){ alert('الرجاء اختيار نوع الإجازة'); return; } if(!formData.start_date){ alert('الرجاء اختيار التاريخ'); return; } if(durationType==='days'&&!formData.end_date){ alert('الرجاء اختيار تاريخ الانتهاء'); return; } if(durationType==='hours'&&(!formData.start_time||!formData.end_time)){ alert('الرجاء اختيار وقت البداية والنهاية'); return; } const btn=form.querySelector('button[type="submit"]'); btn.disabled=true; btn.innerHTML='جاري الإرسال...'; try{ const response=await fetch('{{ route("staff.leave.request") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify(formData)}); const data=await response.json(); if(data.success){ alert('✅ تم إرسال طلب الإجازة بنجاح'); closeLeaveModal(); form.reset(); } else{ alert('❌ حدث خطأ: '+ (data.message||'حاولي مرة أخرى')); } } catch(error){ console.error(error); alert('❌ حدث خطأ في الإرسال'); } finally{ btn.disabled=false; btn.innerHTML='إرسال الطلب'; } });
    
    // ========== دوال سجل الإجازات ==========
    async function showLeaveHistory(){ const modal=document.getElementById('leaveHistoryModal'); modal.classList.remove('hidden'); modal.classList.add('flex'); const content=document.getElementById('leaveHistoryContent'); content.innerHTML='<div class="text-center py-8 text-gray-500">جاري التحميل...</div>'; try{ const res=await fetch('{{ route("staff.leave.history") }}'); const leaves=await res.json(); if(leaves.length===0){ content.innerHTML='<div class="text-center py-8 text-gray-500">لا توجد إجازات سابقة</div>'; return; } content.innerHTML=`<div class="overflow-x-auto"><table class="w-full text-right"><thead><tr style="border-bottom:2px solid #B08D57;"><th class="p-2">نوع الإجازة</th><th class="p-2">التفاصيل</th><th class="p-2">الحالة</th><th class="p-2">ملاحظة الإدارة</th><th class="p-2">تاريخ الطلب</th></tr></thead><tbody>${leaves.map(l=>`<tr style="border-bottom:1px solid #eee;"><td class="p-2">${l.leave_type}</td><td class="p-2" style="color:#B08D57;font-size:13px;">${l.display_text}</td><td class="p-2"><span class="px-2 py-1 rounded-full text-xs" style="background:${l.status_color}20;color:${l.status_color}">${l.status_name}</span></td><td class="p-2 text-sm" style="color:${l.admin_notes?'#ef4444':'#aaa'}">${l.admin_notes||'—'}</td><td class="p-2">${new Date(l.created_at).toLocaleDateString('ar')}</td></tr>`).join('')}</tbody></table></div><div class="mt-3 text-xs text-gray-400 text-center">💡 اضغط على أي صف لرؤية التفاصيل</div>`; } catch(e){ console.error(e); content.innerHTML='<div class="text-center py-8 text-red-500">حدث خطأ في التحميل</div>'; } }
    function showLeaveDetails(id){ fetch(`/staff/leave/${id}`).then(r=>r.json()).then(l=>{ let d=`📋 تفاصيل الإجازة\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n📌 النوع: ${l.leave_type}\n📅 ${l.display_text}\n📝 السبب: ${l.reason||'لا يوجد سبب'}\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n📊 الحالة: ${l.status_name}`; if(l.admin_notes) d+=`\n📝 ملاحظة الإدارة: ${l.admin_notes}`; if(l.reviewed_at) d+=`\n📅 تاريخ المراجعة: ${new Date(l.reviewed_at).toLocaleDateString('ar')}`; alert(d); }).catch(e=>{ console.error(e); alert('حدث خطأ'); }); }
    function closeLeaveHistoryModal(){ const m=document.getElementById('leaveHistoryModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    
    // ========== دوال سجل الرواتب ==========
    async function showSalaryHistory(){ const modal=document.getElementById('salaryHistoryModal'); modal.classList.remove('hidden'); modal.classList.add('flex'); const content=document.getElementById('salaryHistoryContent'); content.innerHTML='<div class="text-center py-8 text-gray-500">جاري التحميل...</div>'; try{ const res=await fetch('{{ route("staff.salary.history") }}'); const salaries=await res.json(); if(salaries.length===0){ content.innerHTML='<div class="text-center py-8 text-gray-500">لا توجد سجلات رواتب سابقة</div>'; return; } content.innerHTML=`<div class="overflow-x-auto"><table class="w-full text-right"><thead><tr style="border-bottom:2px solid #B08D57;"><th class="p-2">الشهر</th><th class="p-2">السنة</th><th class="p-2">الأساسي</th><th class="p-2">الخصم</th><th class="p-2">المكافأة</th><th class="p-2">الصافي</th><th class="p-2">الحالة</th></tr></thead><tbody>${salaries.map(s=>`<tr style="border-bottom:1px solid #eee;cursor:pointer;" onclick="showSalaryDetails(${s.year},${s.month})"><td class="p-2">${s.month_name}</td><td class="p-2">${s.year}</td><td class="p-2">${s.base_salary} د.أ</td><td class="p-2 text-red-600">-${s.deduction} د.أ</td><td class="p-2 text-green-600">+${s.bonus} د.أ</td><td class="p-2 font-bold" style="color:#B08D57;">${s.net_salary} د.أ</td><td class="p-2">${s.is_paid?'<span class="text-green-600">✓ تم الدفع</span>':'<span class="text-yellow-600">⏳ قيد الانتظار</span>'}</span></td></td>`).join('')}</tbody>}</div><div class="mt-3 text-xs text-gray-400 text-center">💡 اضغط على أي صف لرؤية التفاصيل</div>`; } catch(e){ console.error(e); content.innerHTML='<div class="text-center py-8 text-red-500">حدث خطأ في التحميل</div>'; } }
    function closeSalaryHistoryModal(){ const m=document.getElementById('salaryHistoryModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
    async function showSalaryDetails(year,month){ try{ const r=await fetch(`/staff/salary/${year}/${month}`); const s=await r.json(); alert(`📊 تفاصيل الراتب - ${s.month_name} ${s.year}\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n💰 الراتب الأساسي: ${s.base_salary} د.أ\n🏷️ الخصم: - ${s.deduction} د.أ\n🎁 المكافأة: + ${s.bonus} د.أ\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n💵 الراتب الصافي: ${s.net_salary} د.أ\n📝 ملاحظات: ${s.notes||'لا توجد ملاحظات'}\n✅ حالة الدفع: ${s.is_paid?'تم الدفع بتاريخ '+new Date(s.paid_at).toLocaleDateString('ar'):'لم يتم الدفع بعد'}`); } catch(e){ console.error(e); alert('حدث خطأ'); } }
    
    // ========== دوال مساعدة ==========
    function escapeHtml(t){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }
    function formatTime(d){ return new Date(d).toLocaleTimeString('ar',{hour:'2-digit',minute:'2-digit'}); }
    async function updateUnreadCount(){ try{ const r=await fetch('{{ route("chat.unread-count") }}'); const d=await r.json(); const b=document.getElementById('chat-unread-badge'); if(d.count>0&&!document.getElementById('chatPopup').classList.contains('show')){ b.textContent=d.count; b.classList.remove('hidden'); } else b.classList.add('hidden'); } catch(e){ console.error(e); } }
    setInterval(updateUnreadCount,5000); updateUnreadCount();
    document.getElementById('leaveModal')?.addEventListener('click',function(e){ if(e.target===this) closeLeaveModal(); });
    document.getElementById('leaveHistoryModal')?.addEventListener('click',function(e){ if(e.target===this) closeLeaveHistoryModal(); });
    document.getElementById('salaryHistoryModal')?.addEventListener('click',function(e){ if(e.target===this) closeSalaryHistoryModal(); });
</script>


    {{-- ========== شريط العميل (CUSTOMER NAVBAR) ========== --}}
    @elseif(auth()->user()->role === 'customer')
    <nav class="shadow-md border-b sticky top-0 z-50" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="text-2xl font-black tracking-tighter" style="color: #B08D57;">
                        LASHE OUT ✨
                    </a>
                </div>

                {{-- Desktop Navigation للعميل --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('customer.dashboard') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                        الرئيسية
                    </a>
                    <a href="{{ route('customer.bookings.index') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                        حجوزاتي
                    </a>
                    <a href="{{ route('customer.reviews.index') }}" class="font-bold transition hover:opacity-70" style="color: #7C8574;">
                        تقييماتي
                    </a>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 font-bold transition focus:outline-none" style="color: #2B1E1A;">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-sm transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                        
                        <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-2xl shadow-xl z-50" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-t-2xl transition" style="color: #2B1E1A;">
                                <i class="fas fa-user w-5" style="color: #B08D57;"></i>
                                <span>ملفي الشخصي</span>
                            </a>
                            <div class="border-t" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-b-2xl transition w-full text-right" style="color: #2B1E1A;">
                                    <i class="fas fa-sign-out-alt w-5" style="color: #B08D57;"></i>
                                    <span>تسجيل خروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="focus:outline-none" style="color: #7C8574;">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu للعميل --}}
        <div id="mobile-menu" class="hidden md:hidden py-4 px-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(176, 141, 87, 0.1);">
            <div class="flex flex-col gap-3">
                <a href="{{ route('customer.dashboard') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">الرئيسية</a>
                <a href="{{ route('customer.bookings.index') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">حجوزاتي</a>
                <a href="{{ route('customer.reviews.index') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">تقييماتي</a>
                <a href="{{ route('profile.edit') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">ملفي الشخصي</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">
                        تسجيل خروج
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endif

    {{-- Styles and Scripts --}}
    <style>
        [x-cloak] { display: none !important; }
        .rotate-180 { transform: rotate(180deg); }
        .btn-outline-luxury:hover i, 
        .btn-outline-luxury:hover span {
            color: white !important;
        }
    </style>

    <script>
        // Mobile menu toggle for all users
        const mobileButton = document.getElementById('mobile-menu-button');
        if (mobileButton) {
            mobileButton.addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });
        }
    </script>

@endauth