{{-- Sisipkan library marked.js untuk merender format teks dari AI --}}
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

{{-- Widget Chatbot --}}
<div id="chat-widget">
    {{-- Header --}}
    <div class="chat-header">
        <span style="display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H8c-.6 0-1.2.3-1.5.7L5 10s-2.7.6-4.5 1.1c-.8.2-1.5 1-1.5 1.9v3c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <path d="M9 17h6"></path>
                <circle cx="17" cy="17" r="2"></circle>
            </svg> Asisten Mobilku
        </span>
        <button onclick="document.getElementById('chat-widget').style.display='none'" class="chat-close-btn">×</button>
    </div>

    {{-- Messages Area --}}
    <div id="chat-messages">
        <div style="margin-bottom: 12px; display: flex; gap: 8px;">
            <div class="bot-bubble">
                Halo! Saya bisa membantu Anda mencari mobil impian. Sebutkan budget, jumlah kursi, atau bahan bakar yang diinginkan.
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Ketik preferensi mobil...">
        <button onclick="sendMessage()" id="chat-send-btn">Kirim</button>
    </div>
</div>

{{-- Tombol Mengambang --}}
<button onclick="toggleChat()" class="chat-float-btn">
    💬
</button>

<style>
    /* --- STYLING DASAR WIDGET (Desktop) --- */
    #chat-widget {
        position: fixed; bottom: 90px; right: 20px; width: 360px;
        background: #1e293b; border: 1px solid #334155; border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5); display: none;
        z-index: 9999; overflow: hidden; font-family: 'Inter', sans-serif;
        color: #f8fafc; flex-direction: column;
    }
    .chat-header {
        background: #0f172a; padding: 14px 16px; font-weight: 600;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid #334155;
    }
    .chat-close-btn {
        background: none; border: none; color: #94a3b8; cursor: pointer;
        font-size: 24px; line-height: 1; transition: color 0.2s;
    }
    .chat-close-btn:hover { color: #f8fafc; }
    #chat-messages {
        height: 350px; overflow-y: auto; padding: 16px;
        font-size: 14px; background: #1e293b; line-height: 1.5;
    }
    .bot-bubble {
        background: #334155; padding: 10px 14px; border-radius: 0 12px 12px 12px;
        max-width: 85%; color: #e2e8f0;
    }
    .user-bubble {
        background: #3b82f6; color: white; padding: 10px 14px;
        border-radius: 12px 0 12px 12px; max-width: 85%;
    }
    .chat-input-area {
        display: flex; border-top: 1px solid #334155;
        background: #0f172a; padding: 8px;
    }
    #chat-input {
        flex: 1; border: 1px solid #334155; background: #1e293b;
        color: #f8fafc; padding: 10px 14px; border-radius: 20px;
        outline: none; font-size: 14px;
    }
    #chat-send-btn {
        background: #3b82f6; color: white; border: none; padding: 10px 18px;
        margin-left: 8px; border-radius: 20px; cursor: pointer;
        font-weight: 600; transition: background 0.2s;
    }
    #chat-send-btn:hover { background: #2563eb; }
    #chat-send-btn:disabled { background: #64748b; cursor: not-allowed; }
    .chat-float-btn {
        position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px;
        border-radius: 50%; background: #0f172a; color: white;
        border: 2px solid #3b82f6; font-size: 26px; cursor: pointer;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        z-index: 9998; transition: transform 0.2s;
    }
    .chat-float-btn:hover { transform: scale(1.05); }

    /* Styling untuk format balasan AI (Markdown) */
    #chat-messages strong { color: #60a5fa; font-weight: 700; }
    #chat-messages ul { list-style-type: disc; margin-left: 20px; margin-top: 4px; margin-bottom: 8px; }
    #chat-messages ol { list-style-type: decimal; margin-left: 20px; margin-top: 4px; margin-bottom: 8px; }
    #chat-messages li { margin-bottom: 4px; }
    #chat-messages p { margin-bottom: 8px; }
    #chat-messages a {
        display: inline-block; color: #60a5fa; text-decoration: underline;
        font-weight: bold; transition: all 0.2s;
    }
    #chat-messages a:hover { color: #93c5fd; text-decoration: none; }

    /* --- STYLING RESPONSIF UNTUK HP / ANDROID --- */
    @media (max-width: 768px) {
        #chat-widget {
            width: calc(100% - 30px) !important; bottom: 85px !important;
            right: 15px !important; height: 70vh !important; max-height: 600px;
        }
        #chat-messages {
            height: calc(70vh - 120px) !important;
        }
        .chat-float-btn {
            width: 55px !important; height: 55px !important;
            bottom: 15px !important; right: 15px !important; font-size: 24px !important;
        }
    }
</style>

<script>
    function toggleChat() {
        const widget = document.getElementById('chat-widget');
        widget.style.display = widget.style.display === 'none' || widget.style.display === '' ? 'flex' : 'none';
    }

    async function sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message) return;

        const messagesDiv = document.getElementById('chat-messages');
        const sendBtn = document.getElementById('chat-send-btn');

        // Tampilkan pesan user
        messagesDiv.innerHTML += `
            <div style="margin-bottom: 12px; display: flex; gap: 8px; justify-content: flex-end;">
                <div class="user-bubble">${message}</div>
            </div>`;
        input.value = '';
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        // Loading state
        sendBtn.disabled = true;
        sendBtn.textContent = '...';

        const loadingId = 'loading-' + Date.now();
        messagesDiv.innerHTML += `
            <div id="${loadingId}" style="margin-bottom: 12px; display: flex; gap: 8px;">
                <div style="background: #334155; color: #94a3b8; padding: 10px 14px; border-radius: 0 12px 12px 12px; font-style: italic;">
                    Sedang menganalisis data...
                </div>
            </div>`;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        try {
            const res = await fetch('{{ route('chatbot.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    
                },
                body: JSON.stringify({ message: message })
            });

            const data = await res.json();
            document.getElementById(loadingId).remove();

            let reply = data.reply || data.error?.error?.message || 'Maaf, terjadi kesalahan pada server.';
            let formattedReply = marked.parse(reply);

            messagesDiv.innerHTML += `
                <div style="margin-bottom: 12px; display: flex; gap: 8px;">
                    <div class="bot-bubble">${formattedReply}</div>
                </div>`;

        } catch (e) {
            if (document.getElementById(loadingId)) document.getElementById(loadingId).remove();
            
            // Tampilkan error detail
            messagesDiv.innerHTML += `
                <div style="margin-bottom: 12px; display: flex; gap: 8px;">
                    <div style="background: #7f1d1d; color: #fca5a5; padding: 10px 14px; border-radius: 0 12px 12px 12px;">
                        Terjadi kesalahan sistem. Cek Console (F12) untuk detail.
                    </div>
                </div>`;
            console.error("Detail Error Chatbot:", e);
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Kirim';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
    }

    // Kirim saat Enter ditekan
    document.getElementById('chat-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage();
    });
</script>