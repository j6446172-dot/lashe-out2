{{-- ========== شريط العميل (CUSTOMER NAVBAR) ========== --}}
@auth
    @if(auth()->user()->role === 'customer')
        <nav class="shadow-md border-b sticky top-0 z-50" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border-color: rgba(176, 141, 87, 0.2);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    
                    {{-- Logo --}}
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="text-2xl font-black tracking-tighter transition hover:opacity-80" style="color: #B08D57;">
                            LASHE OUT ✨
                        </a>
                    </div>

                    {{-- Desktop Navigation - أزرار للعميل --}}
                    <div class="hidden md:flex items-center gap-3">
                        {{-- زر الرئيسية --}}
                        <a href="{{ route('customer.dashboard') }}" 
                           class="px-4 py-2 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 hover:shadow-md"
                           style="color: #7C8574; background: transparent; border: 1px solid transparent;"
                           onmouseover="this.style.background='#B08D57'; this.style.color='white'; this.style.borderColor='#B08D57';"
                           onmouseout="this.style.background='transparent'; this.style.color='#7C8574'; this.style.borderColor='transparent';">
                            <i class="fas fa-home"></i>
                            <span>الرئيسية</span>
                        </a>
                        
                        {{-- زر حجوزاتي --}}
                        <a href="{{ route('customer.bookings.index') }}" 
                           class="px-4 py-2 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 hover:shadow-md"
                           style="color: #7C8574; background: transparent; border: 1px solid transparent;"
                           onmouseover="this.style.background='#B08D57'; this.style.color='white'; this.style.borderColor='#B08D57';"
                           onmouseout="this.style.background='transparent'; this.style.color='#7C8574'; this.style.borderColor='transparent';">
                            <i class="fas fa-calendar-check"></i>
                            <span>حجوزاتي</span>
                        </a>
                        
                        {{-- زر تقييماتي --}}
                        <a href="{{ route('customer.reviews.index') }}" 
                           class="px-4 py-2 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 hover:shadow-md"
                           style="color: #7C8574; background: transparent; border: 1px solid transparent;"
                           onmouseover="this.style.background='#B08D57'; this.style.color='white'; this.style.borderColor='#B08D57';"
                           onmouseout="this.style.background='transparent'; this.style.color='#7C8574'; this.style.borderColor='transparent';">
                            <i class="fas fa-star"></i>
                            <span>تقييماتي</span>
                        </a>
                    </div>

                    {{-- User Menu --}}
                    <div class="flex items-center gap-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center gap-2 font-bold transition-all duration-300 focus:outline-none px-3 py-2 rounded-xl hover:bg-[#B08D57]/10"
                                    style="color: #2B1E1A;">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{'rotate-180': open}"></i>
                            </button>
                            
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-56 rounded-2xl shadow-xl z-50 overflow-hidden"
                                style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.15);">
                                
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-3 px-4 py-3 transition-all duration-300 hover:bg-[#B08D57]/10"
                                   style="color: #2B1E1A;">
                                    <i class="fas fa-user w-5" style="color: #B08D57;"></i>
                                    <span>ملفي الشخصي</span>
                                </a>
                                
                                <div class="border-t" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center gap-3 px-4 py-3 transition-all duration-300 w-full text-right hover:bg-red-50"
                                            style="color: #2B1E1A;">
                                        <i class="fas fa-sign-out-alt w-5" style="color: #B08D57;"></i>
                                        <span>تسجيل خروج</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Menu Button --}}
                    <div class="md:hidden">
                        <button id="mobile-menu-button-customer" 
                                class="focus:outline-none p-2 rounded-lg transition-all duration-300 hover:bg-[#B08D57]/10"
                                style="color: #7C8574;">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu للعميل --}}
            <div id="mobile-menu-customer" class="hidden md:hidden py-4 px-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-top: 1px solid rgba(176, 141, 87, 0.1);">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 hover:bg-[#B08D57] hover:text-white"
                       style="color: #7C8574;">
                        <i class="fas fa-home w-5"></i>
                        <span>الرئيسية</span>
                    </a>
                    
                    <a href="{{ route('customer.bookings.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 hover:bg-[#B08D57] hover:text-white"
                       style="color: #7C8574;">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span>حجوزاتي</span>
                    </a>
                    
                    <a href="{{ route('customer.reviews.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 hover:bg-[#B08D57] hover:text-white"
                       style="color: #7C8574;">
                        <i class="fas fa-star w-5"></i>
                        <span>تقييماتي</span>
                    </a>
                    
                    <div class="border-t my-2" style="border-color: rgba(176, 141, 87, 0.1);"></div>
                    
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 hover:bg-[#B08D57] hover:text-white"
                       style="color: #7C8574;">
                        <i class="fas fa-user w-5"></i>
                        <span>ملفي الشخصي</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-300 w-full text-right hover:bg-red-500 hover:text-white"
                                style="color: #7C8574;">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>تسجيل خروج</span>
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
            // Mobile menu toggle for customer
            const mobileButtonCustomer = document.getElementById('mobile-menu-button-customer');
            if (mobileButtonCustomer) {
                mobileButtonCustomer.addEventListener('click', function() {
                    const menu = document.getElementById('mobile-menu-customer');
                    menu.classList.toggle('hidden');
                });
            }
        </script>
    @endif
@endauth