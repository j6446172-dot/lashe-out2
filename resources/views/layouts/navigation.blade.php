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

            {{-- Desktop Navigation للموظف --}}
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

            {{-- User Menu with Chat Button --}}
            <div class="flex items-center gap-4">
                {{-- 🔥 زر شات مع المالك - يفتح نافذة شات منبثقة --}}
                <button onclick="openChatWindow()" 
                        class="px-3 py-1.5 rounded-lg text-sm flex items-center gap-2 transition hover:bg-[#B08D57] hover:text-white relative"
                        style="border: 1px solid #B08D57; color: #B08D57; background: rgba(176, 141, 87, 0.05);">
                    <i class="fas fa-comment-dots"></i>
                    <span>شات مع المالك</span>
                    <span id="chat-unread-badge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                </button>

                {{-- قائمة المستخدم للموظف --}}
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

    {{-- Mobile Menu للموظف --}}
    <div id="mobile-menu" class="hidden md:hidden py-4 px-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(176, 141, 87, 0.1);">
        <div class="flex flex-col gap-3">
            <a href="{{ route('staff.dashboard') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">لوحة التحكم</a>
            <a href="{{ route('staff.bookings') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">الحجوزات</a>
            <a href="{{ route('staff.schedule') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">جدول دوامي</a>
            <a href="{{ route('staff.reviews') }}" class="font-bold py-2 transition hover:opacity-70" style="color: #7C8574;">تقييماتي</a>
            
            <button onclick="openChatWindow()" class="font-bold py-2 transition hover:opacity-70 text-right flex items-center gap-2" style="color: #B08D57;">
                <i class="fas fa-comment-dots"></i>
                شات مع المالك
            </button>
            
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

{{-- نافذة الشات المنبثقة --}}
<div id="chatPopup" class="fixed bottom-4 left-4 w-96 bg-white rounded-2xl shadow-2xl z-[100] hidden transition-all duration-300" style="border: 1px solid rgba(176, 141, 87, 0.3);">
    {{-- رأس النافذة --}}
    <div class="px-4 py-3 rounded-t-2xl flex justify-between items-center" style="background: #B08D57;">
        <div class="flex items-center gap-2">
            <i class="fas fa-comment-dots text-white"></i>
            <span class="font-bold text-white">شات مع المالك</span>
            <span class="text-xs text-white/70">متصل</span>
        </div>
        <button onclick="closeChatWindow()" class="text-white/70 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    {{-- منطقة الرسائل --}}
    <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-3" style="background: #F9F7F4;">
        <div class="text-center text-gray-400 text-sm py-10">
            <i class="fas fa-comments text-4xl mb-2 block"></i>
            <p>ابدأ المحادثة مع المالك</p>
        </div>
    </div>
    
    {{-- منطقة الكتابة --}}
    <div class="p-3 border-t" style="border-color: rgba(176, 141, 87, 0.2); background: white;">
        <form id="popupChatForm" class="flex gap-2">
            @csrf
            <input type="text" id="popupMessageInput" 
                   class="flex-1 rounded-xl px-4 py-2 border focus:outline-none focus:border-[#B08D57] text-sm"
                   style="border-color: rgba(176, 141, 87, 0.3);"
                   placeholder="اكتب رسالتك...">
            <button type="submit" class="px-4 py-2 rounded-xl text-white transition hover:opacity-90" style="background: #B08D57;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    let chatInterval = null;
    let lastMessageId = 0;

    function openChatWindow() {
        const popup = document.getElementById('chatPopup');
        popup.classList.remove('hidden');
        popup.classList.add('show');
        
        // تحميل الرسائل السابقة
        loadChatMessages();
        
        // بدء التحديث التلقائي
        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(loadChatMessages, 3000);
        
        // تحديث عدد الرسائل غير المقروءة
        fetch('{{ route("chat.mark-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        // إخفاء البadge
        const badge = document.getElementById('chat-unread-badge');
        if (badge) badge.classList.add('hidden');
    }

    function closeChatWindow() {
        const popup = document.getElementById('chatPopup');
        popup.classList.add('hidden');
        popup.classList.remove('show');
        if (chatInterval) {
            clearInterval(chatInterval);
            chatInterval = null;
        }
    }

    async function loadChatMessages() {
        try {
            const response = await fetch('{{ route("chat.messages") }}');
            const messages = await response.json();
            
            const container = document.getElementById('chatMessages');
            
            if (messages.length > 0) {
                let newLastId = messages[messages.length - 1].id;
                
                if (newLastId > lastMessageId) {
                    // تحديث الواجهة
                    container.innerHTML = '';
                    messages.forEach(msg => {
                        addMessageToPopup(msg);
                    });
                    lastMessageId = newLastId;
                    
                    // تمرير للأسفل
                    container.scrollTop = container.scrollHeight;
                }
            } else {
                if (container.innerHTML === '' || container.innerHTML.includes('ابدأ المحادثة')) {
                    container.innerHTML = `
                        <div class="text-center text-gray-400 text-sm py-10">
                            <i class="fas fa-comments text-4xl mb-2 block"></i>
                            <p>ابدأ المحادثة مع المالك</p>
                        </div>
                    `;
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    function addMessageToPopup(msg) {
        const container = document.getElementById('chatMessages');
        const isMine = msg.from_user_id == {{ auth()->id() }};
        
        // إزالة رسالة البداية إذا وجدت
        if (container.innerHTML.includes('ابدأ المحادثة')) {
            container.innerHTML = '';
        }
        
        const messageDiv = document.createElement('div');
        
        if (isMine) {
            messageDiv.className = 'flex justify-end mb-2';
            messageDiv.innerHTML = `
                <div class="max-w-[80%] bg-[#B08D57] text-white rounded-2xl rounded-br-none px-3 py-2 shadow">
                    <p class="text-sm">${escapeHtml(msg.message)}</p>
                    <p class="text-[10px] text-white/60 mt-1">${formatTime(msg.created_at)}</p>
                </div>
            `;
        } else {
            messageDiv.className = 'flex justify-start mb-2';
            messageDiv.innerHTML = `
                <div class="max-w-[80%] bg-white rounded-2xl rounded-bl-none px-3 py-2 shadow" style="border-right: 2px solid #B08D57;">
                    <div class="flex items-center gap-1 mb-1">
                        <i class="fas fa-crown text-[#B08D57] text-[10px]"></i>
                        <span class="text-[10px] font-bold" style="color: #B08D57;">المالك</span>
                    </div>
                    <p class="text-sm text-gray-700">${escapeHtml(msg.message)}</p>
                    <p class="text-[10px] text-gray-400 mt-1">${formatTime(msg.created_at)}</p>
                </div>
            `;
        }
        
        container.appendChild(messageDiv);
    }

    // إرسال رسالة
    document.getElementById('popupChatForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('popupMessageInput');
        const message = input.value.trim();
        if (!message) return;
        
        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        
        try {
            const response = await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            if (data.success) {
                input.value = '';
                await loadChatMessages();
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            btn.disabled = false;
            input.focus();
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' });
    }

    // تحديث عدد الرسائل غير المقروءة
    async function updateUnreadCount() {
        try {
            const response = await fetch('{{ route("chat.unread-count") }}');
            const data = await response.json();
            const badge = document.getElementById('chat-unread-badge');
            if (data.count > 0 && !document.getElementById('chatPopup').classList.contains('show')) {
                badge.textContent = data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    setInterval(updateUnreadCount, 5000);
    updateUnreadCount();
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