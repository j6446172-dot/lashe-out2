@extends('layouts.app')

@section('content')
<div class="min-h-screen py-20" style="background: linear-gradient(135deg, #F3EDE6, #E8DCD0);">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <div class="rounded-2xl p-6 mb-6 shadow-md" style="background: linear-gradient(135deg, #B08D57, #9a7848);">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">💬 محادثة مع المالك</h1>
                    <p class="text-white/80 mt-1">تواصل مباشر مع إدارة الصالون</p>
                </div>
                <a href="{{ route('staff.dashboard') }}" class="text-white/80 hover:text-white transition">
                    ← العودة
                </a>
            </div>
        </div>

        <div class="rounded-xl overflow-hidden shadow-xl" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(8px);">
            {{-- Chat Header --}}
            <div class="px-6 py-4" style="background: #B08D57;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white">المالك</p>
                        <p class="text-white/70 text-xs">متصل الآن</p>
                    </div>
                </div>
            </div>

            {{-- Messages Area --}}
            <div id="messages-container" class="h-96 overflow-y-auto p-4 space-y-3" style="background: rgba(255, 255, 255, 0.5);">
                @foreach($messages as $msg)
                    @if($msg->from_user_id == auth()->id())
                        {{-- رسالة من الموظف --}}
                        <div class="flex justify-end">
                            <div class="max-w-[70%] bg-[#B08D57] text-white rounded-2xl rounded-br-none px-4 py-2 shadow">
                                <p class="text-sm">{{ $msg->message }}</p>
                                <p class="text-xs text-white/60 mt-1">{{ $msg->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @else
                        {{-- رسالة من المالك --}}
                        <div class="flex justify-start">
                            <div class="max-w-[70%] bg-white rounded-2xl rounded-bl-none px-4 py-2 shadow" style="border-right: 3px solid #B08D57;">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-crown text-[#B08D57] text-xs"></i>
                                    <span class="text-xs font-bold" style="color: #B08D57;">المالك</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $msg->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $msg->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Input Area --}}
            <div class="p-4 border-t" style="border-color: rgba(176, 141, 87, 0.2); background: white;">
                <form id="chat-form" class="flex gap-2">
                    @csrf
                    <input type="text" id="message-input" name="message" 
                           class="flex-1 rounded-xl px-4 py-2 border focus:outline-none focus:border-[#B08D57]"
                           style="border-color: rgba(176, 141, 87, 0.3);"
                           placeholder="اكتب رسالتك هنا...">
                    <button type="submit" class="px-5 py-2 rounded-xl text-white transition hover:opacity-90" style="background: #B08D57;">
                        <i class="fas fa-paper-plane"></i>
                        <span class="hidden sm:inline mr-1">إرسال</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Scroll to bottom of messages
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;

    // Send message via AJAX
    const form = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        // دسابل الزر مؤقتاً
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

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
                // إضافة الرسالة إلى الواجهة
                addMessageToChat(data.message.message, 'sent', new Date());
                messageInput.value = '';
                messageInput.focus();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في إرسال الرسالة');
        } finally {
            submitBtn.disabled = false;
        }
    });

    function addMessageToChat(message, type, time) {
        const messageDiv = document.createElement('div');
        
        if (type === 'sent') {
            messageDiv.className = 'flex justify-end';
            messageDiv.innerHTML = `
                <div class="max-w-[70%] bg-[#B08D57] text-white rounded-2xl rounded-br-none px-4 py-2 shadow">
                    <p class="text-sm">${escapeHtml(message)}</p>
                    <p class="text-xs text-white/60 mt-1">${formatTime(time)}</p>
                </div>
            `;
        } else {
            messageDiv.className = 'flex justify-start';
            messageDiv.innerHTML = `
                <div class="max-w-[70%] bg-white rounded-2xl rounded-bl-none px-4 py-2 shadow" style="border-right: 3px solid #B08D57;">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-crown text-[#B08D57] text-xs"></i>
                        <span class="text-xs font-bold" style="color: #B08D57;">المالك</span>
                    </div>
                    <p class="text-sm text-gray-700">${escapeHtml(message)}</p>
                    <p class="text-xs text-gray-400 mt-1">${formatTime(time)}</p>
                </div>
            `;
        }
        
        container.appendChild(messageDiv);
        container.scrollTop = container.scrollHeight;
    }

    // Auto-refresh messages every 3 seconds
    let lastMessageId = {{ $messages->last()->id ?? 0 }};

    setInterval(async () => {
        try {
            const response = await fetch('{{ route("chat.messages") }}');
            const messages = await response.json();
            
            messages.forEach(msg => {
                if (msg.id > lastMessageId && msg.from_user_id != {{ auth()->id() }}) {
                    addMessageToChat(msg.message, 'received', msg.created_at);
                    lastMessageId = msg.id;
                } else if (msg.id > lastMessageId) {
                    lastMessageId = msg.id;
                }
            });
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }, 3000);

    // Helper functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatTime(date) {
        if (typeof date === 'string') {
            date = new Date(date);
        }
        return date.toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' });
    }
</script>
@endsection