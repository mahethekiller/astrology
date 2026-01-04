@extends('frontend.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Astrologer Chat</h5>
                        <div>
                            <span class="badge bg-success me-2"
                                id="wallet-balance">₹{{ number_format(Auth::user()->wallet->balance ?? 0, 2) }}</span>
                            <span id="chat-timer" class="badge bg-light text-dark me-2">00:00</span>
                            <button id="end-chat-btn" class="btn btn-sm btn-light text-danger">End Chat</button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="card-body overflow-auto" id="messages-container"
                        style="height: 400px; background-color: #f8f9fa;">
                        <div id="messages-list" class="d-flex flex-column gap-2">
                            <!-- Messages will appear here -->
                            <div class="text-center text-muted" id="loading-msg">Connecting to secure chat...</div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="card-footer bg-white">
                        <div class="input-group">
                            <input type="text" id="message-input" class="form-control" placeholder="Type a message..."
                                disabled>
                            <button class="btn btn-primary" id="send-btn" disabled>
                                <i class="fas fa-paper-plane"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://media.twiliocdn.com/sdk/js/conversations/v2.2/twilio-conversations.min.js"></script>
    <script>
        const conversationSid = "{{ $sid }}";
        // We get identity from token now, less reliance on blade var for ID
        let conversation = null;
        let timerInterval = null;
        let billingInterval = null;
        let secondsElapsed = 0;

        // UI Elements
        const messagesList = document.getElementById('messages-list');
        const loadingMsg = document.getElementById('loading-msg');
        const msgInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const timerDisplay = document.getElementById('chat-timer');
        const messagesContainer = document.getElementById('messages-container');
        const endChatBtn = document.getElementById('end-chat-btn');

        // Utility: Add Message to UI
        function addMessageToUI(msg) {
            const isMe = msg.author === 'user_{{ Auth::id() }}'; // Simple check, ideally use token identity
            const div = document.createElement('div');
            div.className = `p-2 rounded rounded-3 ${isMe ? 'bg-primary text-white align-self-end' : 'bg-light border align-self-start'}`;
            div.style.maxWidth = '75%';
            div.textContent = msg.body;
            messagesList.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Utility: Format time
        function formatTime(totalSeconds) {
            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = (totalSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        // 1. Fetch Token & Init
        async function initChat() {
            try {
                const response = await axios.post('{{ route("chat.token") }}');
                const token = response.data.token;

                const client = new Twilio.Conversations.Client(token);

                // Wait for client to be ready
                conversation = await client.getConversationBySid(conversationSid);

                // Load previous messages
                const messages = await conversation.getMessages();
                loadingMsg.remove();
                messages.items.forEach(addMessageToUI);

                // Enable Input
                msgInput.disabled = false;
                sendBtn.disabled = false;

                // Listen for new messages
                conversation.on('messageAdded', addMessageToUI);

                // Start Timers (Duration + Billing)
                startTimers();

            } catch (error) {
                console.error(error);
                loadingMsg.textContent = 'Error connecting to chat. Refresh to try again.';
            }
        }

        // 2. Billing & Timer System
        function startTimers() {
            // Visual Timer
            timerInterval = setInterval(() => {
                secondsElapsed++;
                timerDisplay.textContent = formatTime(secondsElapsed);
            }, 1000);

            // Billing Ping (Every 60s)
            billingInterval = setInterval(sendBillingPing, 60000);

            // Status Polling (Every 5s)
            setInterval(async () => {
                try {
                    const response = await axios.post('{{ route("chat.session.status") }}', { sid: conversationSid });
                    if (response.data.status === 'completed') {
                        alert('Chat has been ended by the astrologer.');
                        window.location.href = "{{ route('chat.history') }}";
                    }
                } catch (error) {
                    console.error("Status Check Failed", error);
                }
            }, 5000);
        }

        async function sendBillingPing() {
            try {
                const response = await axios.post('{{ route("chat.billing.ping") }}', {
                    sid: conversationSid
                });

                if (response.data.remaining_balance !== undefined) {
                    const balance = parseFloat(response.data.remaining_balance).toFixed(2);
                    document.getElementById('wallet-balance').textContent = '₹' + balance;
                }

            } catch (error) {
                console.error("Billing Ping Failed", error);
                if (error.response && error.response.status === 402) {
                    alert("Low Balance! Chat ending.");
                    clearInterval(billingInterval);
                    clearInterval(timerInterval);
                    endChat();
                    window.location.href = "{{ route('wallet.index') }}";
                }
            }
        }

        // 3. Send Message
        async function sendMessage() {
            const text = msgInput.value.trim();
            if (!text || !conversation) return;

            msgInput.value = '';
            try {
                await conversation.sendMessage(text);
            } catch (err) {
                console.error("Send failed", err);
                alert("Failed to send message.");
            }
        }

        // 4. End Chat
        endChatBtn.addEventListener('click', () => {
            if (confirm("Are you sure you want to end this chat?")) {
                endChat();
            }
        });

        async function endChat() {
            try {
                await axios.post('{{ route("chat.end") }}', { sid: conversationSid });
                alert("Chat ended.");
                window.location.href = "{{ route('user.dashboard') }}";
            } catch (e) {
                console.error(e);
                window.location.href = "{{ route('user.dashboard') }}";
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        msgInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Start
        initChat();
    </script>
@endpush