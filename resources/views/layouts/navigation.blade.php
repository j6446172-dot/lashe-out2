@auth
    @if(auth()->user()->role === 'owner')
        {{-- ========== شريط المالك ========== --}}
        <nav class="glass-nav sticky top-0 z-50 border-b" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <a href="{{ route('owner.dashboard') }}" style="font-size: 22px; font-weight: 900; color: #8B6B4A; text-decoration: none;">LASHE OUT 👑</a>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button style="width: 38px; height: 38px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: rgba(200,162,122,0.1); border: none; cursor: pointer; font-size: 18px;">💬</button>
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
                    </div>
                </div>
            </div>
        </nav>
    @else

<nav class="shadow-md border-b sticky top-0 z-50" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            {{-- Logo --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('customer.dashboard') }}" class="text-2xl font-black tracking-tighter" style="color: #B08D57;">
                    LASHE OUT ✨
                </a>
            </div>

            {{-- Desktop Navigation --}}
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

            {{-- User Menu with Alpine.js --}}
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

    {{-- Mobile Menu --}}
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

<style>
    [x-cloak] { display: none !important; }
    .rotate-180 { transform: rotate(180deg); }
</style>

<script>
    // Mobile menu toggle
    const mobileButton = document.getElementById('mobile-menu-button');
    if (mobileButton) {
        mobileButton.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    }
</script>
    @endif
@endauth