@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 pt-20 pb-12">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                
                {{-- Header --}}
                <div class="rounded-2xl p-6 text-center mb-6 shadow-md"
                     style="background: linear-gradient(135deg, #B08D57, #9a7848);">
                    <h1 class="text-2xl font-bold" style="color: #F3EDE6;">👩‍💼 الموظفات</h1>
                    <p class="text-sm mt-1" style="color: rgba(243, 237, 230, 0.8);">إدارة فريق العمل</p>
                </div>

                {{-- بطاقات الإحصائيات --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">👥 إجمالي الموظفات</p>
                        <p class="text-2xl font-black mt-2" style="color: #B08D57;">{{ $totalStaff ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🟢 نشطات</p>
                        <p class="text-2xl font-black mt-2" style="color: #10b981;">{{ $activeStaff ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">🔴 في إجازة</p>
                        <p class="text-2xl font-black mt-2" style="color: #ef4444;">{{ $onLeaveStaff ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl p-5 text-center" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                        <p class="text-sm" style="color: #7C8574;">⭐ متوسط التقييم</p>
                        <p class="text-2xl font-black mt-2" style="color: #f59e0b;">{{ number_format($avgRating ?? 0, 1) }} ⭐</p>
                    </div>
                </div>

                {{-- أزرار --}}
                <div class="flex justify-between items-center mb-4">
                    <button onclick="openAddModal()" class="px-6 py-2.5 rounded-xl font-bold transition shadow-md" style="background: #B08D57; color: #F3EDE6;">➕ إضافة موظفة</button>
                    <a href="{{ route('owner.dashboard') }}" class="px-5 py-2.5 rounded-xl font-bold transition" style="background: rgba(176, 141, 87, 0.1); color: #B08D57; text-decoration: none;">← العودة</a>
                </div>

                {{-- ========== جدول الموظفات ========== --}}
                <div class="rounded-xl p-6 overflow-x-auto" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border: 1px solid rgba(176, 141, 87, 0.15);">
                    <table class="w-full text-right" style="border-collapse: collapse; min-width: 1000px;">
                        <thead>
                            <tr style="border-bottom: 2px solid rgba(176, 141, 87, 0.2); color: #B08D57;">
                                <th class="p-3">الموظفة</th>
                                <th class="p-3">التخصص</th>
                                <th class="p-3">حجوزات</th>
                                <th class="p-3">إلغاء</th>
                                <th class="p-3">تقييم</th>
                                <th class="p-3">💰 أساسي</th>
                                <th class="p-3">🏷️ خصم</th>
                                <th class="p-3">🎁 مكافأة</th>
                                <th class="p-3">💵 صافي</th>
                                <th class="p-3">عرض</th>
                                <th class="p-3">💬 تواصل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffList ?? [] as $staff)
                            <tr style="border-bottom: 1px solid rgba(176, 141, 87, 0.1);">
                                <td class="p-3 font-bold" style="color: #2B1E1A;">{{ $staff['name'] }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $staff['specialty'] ?? '—' }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ $staff['total_bookings'] ?? 0 }}</td>
                                <td class="p-3" style="color: {{ ($staff['cancel_rate'] ?? 0) > 15 ? '#ef4444' : '#10b981' }};">{{ $staff['cancel_rate'] ?? 0 }}%</td>
                                <td class="p-3" style="color: #f59e0b;">⭐ {{ number_format($staff['rating'] ?? 0, 1) }}</td>
                                <td class="p-3" style="color: #7C8574;">{{ number_format($staff['base_salary'] ?? 0) }} د.أ</td>
                                <td class="p-3" style="color: #ef4444;">{{ number_format($staff['deduction'] ?? 0) }} د.أ</td>
                                <td class="p-3" style="color: #10b981;">{{ number_format($staff['bonus'] ?? 0) }} د.أ</td>
                                <td class="p-3 font-bold" style="color: #2B1E1A;">{{ number_format($staff['net_salary'] ?? 0) }} د.أ</td>
                                <td class="p-3">
                                    <button onclick="showDetail({{ $staff['id'] }})" class="text-xl hover:scale-110 transition">👁️</button>
                                </td>
                                <td class="p-3">
                                    <button onclick="openChatWithStaff({{ $staff['id'] }}, '{{ addslashes($staff['name']) }}')" 
                                            class="px-3 py-1.5 rounded-lg text-sm flex items-center gap-1 transition hover:bg-[#B08D57] hover:text-white"
                                            style="border: 1px solid #B08D57; color: #B08D57; background: rgba(176, 141, 87, 0.05);">
                                        <i class="fas fa-comment-dots"></i>
                                        <span>تواصل</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="p-8 text-center" style="color: #7C8574;">
                                    <i class="fas fa-user-slash text-4xl mb-2 block"></i>
                                    لا توجد موظفات
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- نافذة تفاصيل الموظفة --}}
<div id="staffModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-right" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h3 class="font-bold text-lg" style="color: #F3EDE6;">👩‍💼 تفاصيل الموظفة</h3>
        </div>
        <div class="p-6" id="modalContent"></div>
        <div class="p-4 flex gap-3 justify-center border-t" style="border-color: rgba(176, 141, 87, 0.1);">
            <button onclick="closeModal()" class="px-6 py-2 rounded-lg" style="background: rgba(124, 133, 116, 0.1); color: #7C8574;">✕ إغلاق</button>
        </div>
    </div>
</div>

{{-- نافذة إضافة موظفة --}}
<div id="addStaffModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);">
        <div class="p-4 text-right" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <h3 class="font-bold text-lg" style="color: #F3EDE6;">➕ إضافة موظفة</h3>
        </div>
        <div class="p-6">
            <form id="addStaffForm">
                <input type="text" name="name" placeholder="الاسم" required class="w-full mb-3 px-4 py-3 rounded-xl" style="border:1px solid rgba(176,141,87,0.3);">
                <input type="email" name="email" placeholder="الإيميل" required class="w-full mb-3 px-4 py-3 rounded-xl" style="border:1px solid rgba(176,141,87,0.3);">
                <input type="text" name="phone" placeholder="الهاتف" class="w-full mb-3 px-4 py-3 rounded-xl" style="border:1px solid rgba(176,141,87,0.3);">
                <input type="text" name="specialty" placeholder="التخصص" class="w-full mb-3 px-4 py-3 rounded-xl" style="border:1px solid rgba(176,141,87,0.3);">
                <input type="number" name="salary" placeholder="الراتب" value="350" class="w-full mb-4 px-4 py-3 rounded-xl" style="border:1px solid rgba(176,141,87,0.3);">
                <div class="flex gap-3">
                    <button type="button" onclick="addStaff()" class="flex-1 py-3 rounded-xl font-bold" style="background:#B08D57;color:white;">✅ إضافة</button>
                    <button type="button" onclick="closeAddModal()" class="flex-1 py-3 rounded-xl font-bold" style="background:rgba(124,133,116,0.1);color:#7C8574;">✕ إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- نافذة شات مع الموظفة --}}
<div id="staffChatModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[100]">
    <div class="rounded-2xl w-full max-w-md mx-4 overflow-hidden shadow-2xl" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(176, 141, 87, 0.3);">
        <div class="px-4 py-3 flex justify-between items-center" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex items-center gap-2">
                <i class="fas fa-comment-dots text-white"></i>
                <span class="font-bold text-white" id="chatStaffName">محادثة مع الموظفة</span>
            </div>
            <button onclick="closeStaffChat()" class="text-white/70 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="staffChatMessages" class="h-96 overflow-y-auto p-4 space-y-3" style="background: #F9F7F4;">
            <div class="text-center text-gray-400 text-sm py-10">
                <i class="fas fa-comments text-4xl mb-2 block"></i>
                <p>ابدأ المحادثة مع الموظفة</p>
            </div>
        </div>
        
        <div class="p-3 border-t" style="border-color: rgba(176, 141, 87, 0.2); background: white;">
            <form id="staffChatForm" class="flex gap-2">
                @csrf
                <input type="hidden" id="staffChatId" value="">
                <input type="text" id="staffChatMessage" 
                       class="flex-1 rounded-xl px-4 py-2 border focus:outline-none focus:border-[#B08D57] text-sm"
                       style="border-color: rgba(176, 141, 87, 0.3);"
                       placeholder="اكتب رسالتك...">
                <button type="submit" class="px-4 py-2 rounded-xl text-white transition hover:opacity-90" style="background: #B08D57;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // دوال النوافذ
    function showDetail(id) {
        document.getElementById('staffModal').classList.remove('hidden');
        document.getElementById('staffModal').classList.add('flex');
        document.getElementById('modalContent').innerHTML = '<p class="text-center" style="color:#7C8574;">جاري التحميل...</p>';
        fetch(`/owner/staff-detail/${id}`).then(r=>r.json()).then(data=>{
            document.getElementById('modalContent').innerHTML = `
                <div class="mb-4"><strong style="color:#2B1E1A;">📊 إحصائيات</strong></div>
                <div class="mb-4 p-4 rounded-xl" style="background:#f9f9f9;">
                    <p>📅 حجوزات الشهر: ${data.total_bookings}</p>
                    <p>❌ نسبة الإلغاء: ${data.cancel_rate}%</p>
                    <p>⭐ التقييم: ${data.rating}</p>
                    <p>💰 إيراداتها: ${data.revenue} د.أ</p>
                </div>
                <div class="mb-4"><strong style="color:#2B1E1A;">💼 التفاصيل المالية</strong></div>
                <div class="p-4 rounded-xl" style="background:#f9f9f9;">
                    <p>💰 الأساسي: ${data.base_salary} د.أ</p>
                    <p style="color:#ef4444;">🏷️ الخصم: -${data.deduction} د.أ</p>
                    <p style="color:#10b981;">🎁 المكافأة: +${data.bonus} د.أ</p>
                    <hr class="my-2">
                    <p style="font-weight:bold;">💵 الصافي: ${data.net_salary} د.أ</p>
                </div>
            `;
        });
    }
    
    function closeModal(){ 
        document.getElementById('staffModal').classList.add('hidden'); 
        document.getElementById('staffModal').classList.remove('flex'); 
    }
    
    function openAddModal(){ 
        document.getElementById('addStaffModal').classList.remove('hidden'); 
        document.getElementById('addStaffModal').classList.add('flex'); 
    }
    
    function closeAddModal(){ 
        document.getElementById('addStaffModal').classList.add('hidden'); 
        document.getElementById('addStaffModal').classList.remove('flex'); 
    }
    
    function addStaff(){ 
        const form=document.getElementById('addStaffForm'); 
        const data=new FormData(form); 
        fetch('/owner/staff/add',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:new URLSearchParams(data)
        }).then(r=>r.json()).then(res=>{
            if(res.success) location.reload(); 
            else alert(res.message||'خطأ');
        }); 
    }
    
    document.getElementById('staffModal').addEventListener('click',function(e){
        if(e.target===this) closeModal();
    });

    // دوال الشات
    let staffChatInterval = null;
    let currentStaffId = null;
    let lastStaffMessageId = 0;

    function openChatWithStaff(staffId, staffName) {
        currentStaffId = staffId;
        document.getElementById('chatStaffName').innerHTML = `<i class="fas fa-comment-dots ml-1"></i> محادثة مع ${staffName}`;
        document.getElementById('staffChatId').value = staffId;
        
        const modal = document.getElementById('staffChatModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        loadStaffMessages(staffId);
        
        if (staffChatInterval) clearInterval(staffChatInterval);
        staffChatInterval = setInterval(() => loadStaffMessages(currentStaffId), 3000);
    }

    function closeStaffChat() {
        const modal = document.getElementById('staffChatModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (staffChatInterval) {
            clearInterval(staffChatInterval);
            staffChatInterval = null;
        }
        currentStaffId = null;
    }

    async function loadStaffMessages(staffId) {
        if (!staffId) return;
        
        try {
            const response = await fetch(`/owner/chat/staff/${staffId}/messages`);
            const messages = await response.json();
            const container = document.getElementById('staffChatMessages');
            
            if (messages.length > 0) {
                let newLastId = messages[messages.length - 1].id;
                
                if (newLastId > lastStaffMessageId) {
                    container.innerHTML = '';
                    messages.forEach(msg => addStaffMessageToPopup(msg));
                    lastStaffMessageId = newLastId;
                    container.scrollTop = container.scrollHeight;
                }
            } else {
                if (container.innerHTML === '' || container.innerHTML.includes('ابدأ المحادثة')) {
                    container.innerHTML = `
                        <div class="text-center text-gray-400 text-sm py-10">
                            <i class="fas fa-comments text-4xl mb-2 block"></i>
                            <p>ابدأ المحادثة مع الموظفة</p>
                        </div>
                    `;
                }
            }
        } catch (error) {
            console.error('Error loading staff messages:', error);
        }
    }

    function addStaffMessageToPopup(msg) {
        const container = document.getElementById('staffChatMessages');
        const isMine = msg.from_user_id == {{ auth()->id() }};
        
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
                        <i class="fas fa-user-circle text-[#B08D57] text-[10px]"></i>
                        <span class="text-[10px] font-bold" style="color: #B08D57;">الموظفة</span>
                    </div>
                    <p class="text-sm text-gray-700">${escapeHtml(msg.message)}</p>
                    <p class="text-[10px] text-gray-400 mt-1">${formatTime(msg.created_at)}</p>
                </div>
            `;
        }
        
        container.appendChild(messageDiv);
    }

    document.getElementById('staffChatForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const staffId = document.getElementById('staffChatId').value;
        const message = document.getElementById('staffChatMessage').value.trim();
        if (!message || !staffId) return;
        
        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        
        try {
            const response = await fetch('{{ route("owner.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    message: message,
                    receiver_id: staffId 
                })
            });
            
            const data = await response.json();
            if (data.success) {
                document.getElementById('staffChatMessage').value = '';
                await loadStaffMessages(staffId);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            btn.disabled = false;
            document.getElementById('staffChatMessage').focus();
        }
    });

    document.getElementById('staffChatModal').addEventListener('click', function(e) {
        if (e.target === this) closeStaffChat();
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
</script>
@endsection