{{-- ========== OWNER NAVBAR ========== --}}
@if(auth()->user()->role === 'owner')
<nav class="glass-nav sticky top-0 z-50 border-b" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-16">

            <a href="{{ route('owner.dashboard') }}" style="font-size: 22px; font-weight: 900; color: #8B6B4A; text-decoration: none;">
                LASHE OUT 👑
            </a>

            <div style="display: flex; align-items: center; gap: 10px;">

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 font-bold transition focus:outline-none" style="color: #2B1E1A;">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-sm transition-transform" :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-2xl shadow-xl z-50"
                        style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-t-2xl transition" style="color: #2B1E1A;">
                            <i class="fas fa-user w-5" style="color: #B08D57;"></i>
                            <span>ملفي الشخصي</span>
                        </a>

                        <div class="border-t"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-b-2xl transition w-full text-right">
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
@endif