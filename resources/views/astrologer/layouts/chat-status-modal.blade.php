<!-- Global Chat Management Modal -->
<div class="modal fade" id="astrologerChatManagementModal" tabindex="-1" aria-labelledby="chatManagementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatManagementModalLabel">Chat Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="chat-status-container-global" class="mb-4">
                    <i class="bi bi-chat-left-dots fa-3x text-muted mb-3" id="chat-status-icon-global"
                        style="font-size: 3rem; display: block;"></i>
                    <h4 id="chat-status-text-global">Offline</h4>
                    <p class="text-muted small">Go online to start receiving chat requests from users.</p>
                </div>
                <button id="toggle-chat-online-btn-global" class="btn btn-primary w-100 rounded-pill py-2">
                    <i class="bi bi-power me-2"></i> Go Online
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleChatBtn = document.getElementById('toggle-chat-online-btn-global');
        const chatStatusText = document.getElementById('chat-status-text-global');
        const chatStatusIcon = document.getElementById('chat-status-icon-global');
        const chatHeaderIndicator = document.getElementById('chat-status-indicator');
        const chatHeaderText = document.getElementById('chat-status-indicator-text');

        // Initial status from database (injected via Blade or fetched via API)
        let isChatOnline = {{ (auth()->check() && auth()->user()->astrologerProfile && auth()->user()->astrologerProfile->is_chat_online) ? 'true' : 'false' }};

        // Initialize UI
        updateChatUIState(isChatOnline);

        toggleChatBtn.addEventListener('click', async () => {
            const newStatus = !isChatOnline;
            try {
                toggleChatBtn.disabled = true;
                toggleChatBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';

                await axios.post('{{ route("astrologer.status.toggle") }}', {
                    type: 'chat',
                    status: newStatus
                });

                isChatOnline = newStatus;
                updateChatUIState(isChatOnline);
            } catch (error) {
                console.error("Failed to toggle chat status:", error);
                alert('Error updating chat status. Please try again.');
            } finally {
                toggleChatBtn.disabled = false;
            }
        });

        function updateChatUIState(online) {
            if (online) {
                chatStatusText.textContent = "Online";
                chatStatusText.className = "text-success fw-bold";
                chatStatusIcon.className = "bi bi-chat-dots-fill text-success mb-3";
                chatStatusIcon.style.fontSize = "3rem";

                toggleChatBtn.className = "btn btn-danger w-100 rounded-pill py-2";
                toggleChatBtn.innerHTML = '<i class="bi bi-power me-2"></i> Go Offline';

                if (chatHeaderIndicator) {
                    chatHeaderIndicator.classList.remove('text-muted');
                    chatHeaderIndicator.classList.add('text-primary');
                    if (chatHeaderText) chatHeaderText.textContent = 'Online';
                }
            } else {
                chatStatusText.textContent = "Offline";
                chatStatusText.className = "text-muted";
                chatStatusIcon.className = "bi bi-chat-left-dots text-muted mb-3";
                chatStatusIcon.style.fontSize = "3rem";

                toggleChatBtn.className = "btn btn-primary w-100 rounded-pill py-2";
                toggleChatBtn.innerHTML = '<i class="bi bi-power me-2"></i> Go Online';

                if (chatHeaderIndicator) {
                    chatHeaderIndicator.classList.remove('text-primary');
                    chatHeaderIndicator.classList.add('text-muted');
                    if (chatHeaderText) chatHeaderText.textContent = 'Offline';
                }
            }
            // Dispatch event for other listeners (like dashboard settings)
            window.dispatchEvent(new CustomEvent('chatStatusChanged', { detail: { isOnline: online } }));
        }
    });
</script>