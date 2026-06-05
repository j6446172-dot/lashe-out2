{{-- ========== OWNER NAVBAR ========== --}}
@if(auth()->user()->role === 'owner')
<nav class="glass-nav sticky top-0 z-50 border-b" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-center items-center h-16">
            <a href="{{ route('owner.dashboard') }}" style="font-size: 22px; font-weight: 900; color: #8B6B4A; text-decoration: none;">
                LASHE OUT 👑
            </a>
        </div>
    </div>
</nav>
@endif