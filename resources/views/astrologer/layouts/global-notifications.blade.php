<!-- Global Notification Popups -->
<div class="modal fade" id="globalChatRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 1.5rem;">
            <div class="modal-body p-0">
                <div class="p-4 text-center bg-primary text-white">
                    <div class="mb-3">
                        <div class="spinner-grow text-white" style="width: 3rem; height: 3rem;" role="status"></div>
                    </div>
                    <h3 class="fw-bold mb-1">New Chat Request!</h3>
                    <p class="mb-0 opacity-75">A user is waiting to connect with you.</p>
                </div>

                <div class="p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <div id="popup-user-initial"
                            class="flex-shrink-0 avatar-circle-large me-3 text-white fw-bold d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.5rem;">
                            U
                        </div>
                        <div class="flex-grow-1">
                            <h5 id="popup-user-name" class="mb-0 fw-bold">User Name</h5>
                            <small id="popup-user-phone" class="text-muted">Phone Number</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="#" id="popup-accept-btn"
                            class="btn btn-success btn-lg rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Accept & Start Chat
                        </a>
                        <a href="#" id="popup-reject-btn"
                            class="btn btn-link text-danger text-decoration-none fw-semibold">
                            Reject Request
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Audio -->
<audio id="notificationSound" preload="auto" loop>
    <source src="{{ asset('sounds/ring.mp3') }}" type="audio/mpeg">
</audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let lastRequestsCount = 0;
        let activePopupId = null;
        const notificationModal = new bootstrap.Modal(document.getElementById('globalChatRequestModal'));
        const notificationSound = document.getElementById('notificationSound');

        async function pollRequests() {
            try {
                const response = await axios.get("{{ route('astrologer.requests.pending') }}");
                const chatRequests = response.data.chatRequests;
                const callRequests = response.data.callRequests;

                // Dispatch global event for dashboard to handle table updates
                window.dispatchEvent(new CustomEvent('astrologer:requests-updated', {
                    detail: { chatRequests, callRequests }
                }));

                // Handle Chat Popup Logic
                if (chatRequests.length > 0) {
                    const latestReq = chatRequests[0];

                    // Show popup only if it's a new request we haven't shown yet
                    if (latestReq.id !== activePopupId) {
                        showChatPopup(latestReq);
                    }
                } else {
                    // Hide modal if no requests (e.g. they expired or were handled elsewhere)
                    if (activePopupId) {
                        notificationModal.hide();
                        activePopupId = null;
                    }
                }

                lastRequestsCount = chatRequests.length;
            } catch (error) {
                console.error('Global notification polling failed:', error);
            }
        }

        function showChatPopup(req) {
            activePopupId = req.id;

            document.getElementById('popup-user-name').textContent = req.user_name;
            document.getElementById('popup-user-phone').textContent = req.user_phone || 'Private Number';
            document.getElementById('popup-user-initial').textContent = req.user_initial;
            document.getElementById('popup-accept-btn').href = req.accept_url;
            document.getElementById('popup-reject-btn').href = req.reject_url;

            notificationModal.show();

            // Play notification sound
            try {
                notificationSound.currentTime = 0;
                notificationSound.play();
            } catch (e) {
                console.warn('Audio play blocked by browser policies');
            }
        }

        // Stop sound when modal is hidden
        document.getElementById('globalChatRequestModal').addEventListener('hidden.bs.modal', function () {
            notificationSound.pause();
            notificationSound.currentTime = 0;
        });

        // Start polling every 5 seconds
        setInterval(pollRequests, 5000);
        pollRequests(); // Initial check
    });
</script>

<style>
    .avatar-circle-large {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>