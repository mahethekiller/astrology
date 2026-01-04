@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid" style="height: 80vh;">
        <div class="row h-100">
            <div class="col-12 h-100">
                <div class="card h-100 shadow">
                    <!-- Header -->
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chat Session (Astrologer View)</h5>
                        <div>
                            <span id="chat-timer" class="badge bg-light text-dark me-2">00:00</span>
                            <button id="end-chat-btn" class="btn btn-sm btn-light text-danger">End Chat</button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="card-body overflow-auto" id="messages-container" style="background-color: #f8f9fa;">
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
                            <button class="btn btn-success" id="send-btn" disabled>
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
    {{-- Use Admin Layout usually has scripts stack? --}}
    <script src="https://media.twiliocdn.com/sdk/js/conversations/v2.2/twilio-conversations.min.js"></script>
    {{-- Axios is assumed to be available or added via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const conversationSid = "{{ $sid }}";
        const userIdentity = "user_{{ $user->id }}";
        // For Astrologer view, user ID used validation is tricky if using same identity logic
        // But let's assume identity consistent

        let conversation = null;
        let timerInterval = null;
        let statusInterval = null;
        let secondsElapsed = 0;

        // UI Elements
        const messagesList = document.getElementById('messages-list');
        const loadingMsg = document.getElementById('loading-msg');
        const msgInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const timerDisplay = document.getElementById('chat-timer');
        const messagesContainer = document.getElementById('messages-container');
        const endChatBtn = document.getElementById('end-chat-btn');

        // Utility: Format time
        function formatTime(totalSeconds) {
            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = (totalSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        // Utility: Add Message to UI
        function addMessageToUI(msg) {
            const isMe = msg.author === userIdentity;
            const div = document.createElement('div');
            div.className = `p-2 rounded rounded-3 ${isMe ? 'bg-success text-white align-self-end' : 'bg-light border align-self-start'}`;
            div.style.maxWidth = '75%';
            div.textContent = msg.body;
            messagesList.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // 1. Fetch Token
        async function initChat() {
            try {
                // Note: Reuse the frontend token endpoint for simplicity, assuming session Auth works same
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

                // Start Timer (Passive, just for info)
                startTimers();

                // Start Status Polling (to detect if User ended it)
                startStatusPolling();

            } catch (error) {
                console.error(error);
                loadingMsg.textContent = 'Error connecting to chat. Refresh to try again.';
            }
        }

        // 2. Timer System (Visual Only)
        function startTimers() {
            timerInterval = setInterval(() => {
                secondsElapsed++;
                timerDisplay.textContent = formatTime(secondsElapsed);
            }, 1000);
        }

        // 3. Status Polling (Check if chat is still active)
        // Since we don't have a direct status endpoint for 'room', we can check Request Status
        // BUT we need the Request ID.
        // Alternative: We can try to use conversation attributes or just rely on 'pings' if implemented.
        // Let's implement a simple check endpoint or reuse existing logic.
        // For now, Astrologer can just rely on manual end, or we can poll our Backend status.
        // Let's verify availability of RequestID.

        // 4. End Chat Logic
        endChatBtn.addEventListener('click', async () => {
            if (confirm("Are you sure you want to end this chat?")) {
                endChat();
            }
        });

        async function endChat() {
            try {
                await axios.post('{{ route("chat.end") }}', { sid: conversationSid });
                alert("Chat ended.");
                window.location.href = "{{ route('astrologer.dashboard') }}";
            } catch (e) {
                console.error(e);
                alert("Error ending chat");
            }
        }

        function startStatusPolling() {
            statusInterval = setInterval(async () => {
                try {
                    const response = await axios.post('{{ route("chat.session.status") }}', { sid: conversationSid });
                    if (response.data.status === 'completed') {
                        clearInterval(statusInterval);
                        clearInterval(timerInterval);
                        alert('Chat has been ended by the user.');
                        window.location.href = "{{ route('astrologer.dashboard') }}";
                    }
                } catch (error) {
                    console.error("Status Check Failed", error);
                }
            }, 5000); // Check every 5 seconds
        }

        // 5. Send Message
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

        sendBtn.addEventListener('click', sendMessage);
        msgInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Start
        initChat();
    </script>
@endpush