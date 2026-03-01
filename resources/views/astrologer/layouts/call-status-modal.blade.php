<!-- Global Call Management Modal -->
<div class="modal fade" id="astrologerCallManagementModal" tabindex="-1" aria-labelledby="callManagementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="callManagementModalLabel">Call Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="status-container-global" class="mb-4">
                    <i class="fas fa-phone-slash fa-3x text-muted mb-3" id="status-icon-global"></i>
                    <h4 id="status-text-global">Offline</h4>
                    <p class="text-muted small">Go online to start receiving calls from users.</p>
                </div>
                <button id="toggle-online-btn-global" class="btn btn-primary w-100 rounded-pill py-2">
                    <i class="fas fa-power-off me-2"></i> Go Online
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Incoming Call Modal -->
<div class="modal fade" id="incomingCallModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 1.5rem;">
            <div class="modal-body p-0">
                <div class="p-4 text-center bg-success text-white">
                    <div class="mb-3">
                        <div class="spinner-grow text-white" style="width: 3rem; height: 3rem;" role="status"></div>
                    </div>
                    <h3 class="fw-bold mb-1">Incoming Voice Call</h3>
                    <p class="mb-0 opacity-75 text-white">A user is waiting to speak with you.</p>
                </div>

                <div class="p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <div id="call-popup-user-initial"
                            class="flex-shrink-0 avatar-circle-large me-3 text-white fw-bold d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #2af598 0%, #009efd 100%); font-size: 1.5rem;">
                            ?
                        </div>
                        <div class="flex-grow-1">
                            <h5 id="call-popup-user-name" class="mb-0 fw-bold">Connecting...</h5>
                            <small id="call-popup-user-phone" class="text-muted">Incoming Voice Call</small>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="button" id="accept-btn"
                            class="btn btn-success btn-lg rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-telephone-fill me-2"></i> Accept Call
                        </button>
                        <button type="button" id="reject-btn"
                            class="btn btn-link text-danger text-decoration-none fw-semibold">
                            Decline & Hangup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- In Call Modal -->
<div class="modal fade" id="inCallModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4 bg-dark text-white">
            <div class="modal-body">
                <h4 class="text-success">Connected</h4>
                <h1 class="display-4 fw-bold my-4" id="call-timer-global">00:00</h1>
                <button type="button" class="btn btn-danger btn-lg rounded-pill px-5" id="hangup-btn">
                    <i class="fas fa-phone-slash me-2"></i> End Call
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Call Notification Audio -->
<audio id="callNotificationSound" preload="auto" loop>
    <source src="{{ asset('sounds/ring.mp3') }}" type="audio/mpeg">
</audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let device;
        let activeConnection;
        let timerInterval;
        let seconds = 0;

        const toggleBtn = document.getElementById('toggle-online-btn-global');
        const statusText = document.getElementById('status-text-global');
        const statusIcon = document.getElementById('status-icon-global');
        const headerIndicator = document.getElementById('call-status-indicator');

        // Modals
        const incomingModal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
        const inCallModal = new bootstrap.Modal(document.getElementById('inCallModal'));
        const managementModal = new bootstrap.Modal(document.getElementById('astrologerCallManagementModal'));

        // Buttons
        const acceptBtn = document.getElementById('accept-btn');
        const rejectBtn = document.getElementById('reject-btn');
        const hangupBtn = document.getElementById('hangup-btn');
        const callTimer = document.getElementById('call-timer-global');
        const callNotificationSound = document.getElementById('callNotificationSound');

        // Metadata Sync for Call Popup
        let latestCallRequests = [];
        window.addEventListener('astrologer:requests-updated', function (event) {
            latestCallRequests = event.detail.callRequests;

            // If we have an active incoming call but no metadata yet, try to find it now
            if (activeConnection && document.getElementById('call-popup-user-name').textContent === "Connecting...") {
                syncIncomingCallMetadata();
            }
        });

        function syncIncomingCallMetadata() {
            if (latestCallRequests.length > 0) {
                // Usually the most recent pending call is the one ringing
                const req = latestCallRequests[0];
                const nameEl = document.getElementById('call-popup-user-name');
                const phoneEl = document.getElementById('call-popup-user-phone');
                const initialEl = document.getElementById('call-popup-user-initial');

                if (nameEl) nameEl.textContent = req.user_name || "User";
                if (phoneEl) phoneEl.textContent = req.user_phone || "Incoming Voice Call";
                if (initialEl) initialEl.textContent = req.user_initial || "?";
            }
        }

        // Initial status from database
        let isOnline = {{ auth()->user()->astrologerProfile->is_call_online ? 'true' : 'false' }};

        // Auto-reconnect if previously online
        if (isOnline) {
            goOnline(true); // pass true to skip database update on initial load
        } else {
            updateUIState(false);
        }

        toggleBtn.addEventListener('click', async () => {
            if (!isOnline) {
                await goOnline();
            } else {
                await goOffline();
            }
        });

        async function goOnline(isInitialLoad = false) {
            try {
                toggleBtn.disabled = true;
                toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Connecting...';

                const response = await axios.post('{{ route("call.token") }}', { is_astrologer: true });
                const token = response.data.token;

                device = new Twilio.Device(token, {
                    codecPreferences: ['opus', 'pcmu'],
                    fakeLocalDTMF: true,
                    enableRingingState: true,
                    debug: true
                });

                device.on('registered', async function () {
                    isOnline = true;

                    // Only update database if it's not the initial auto-reconnect
                    if (!isInitialLoad) {
                        try {
                            await axios.post('{{ route("call.toggle-status") }}', { is_online: true });
                        } catch (e) {
                            console.error("Failed to update status in DB", e);
                        }
                    }

                    updateUIState(true);
                });

                device.on('error', function (error) {
                    console.error("Twilio Device Error:", error);
                    goOffline();
                });

                device.on('incoming', function (conn) {
                    activeConnection = conn;

                    // Play call ringtone
                    try {
                        callNotificationSound.currentTime = 0;
                        callNotificationSound.play();
                    } catch (e) {
                        console.warn('Call audio play blocked');
                    }

                    // Reset modal to default before showing
                    const nameEl = document.getElementById('call-popup-user-name');
                    const phoneEl = document.getElementById('call-popup-user-phone');
                    const initialEl = document.getElementById('call-popup-user-initial');

                    if (nameEl) nameEl.textContent = "Connecting...";
                    if (phoneEl) phoneEl.textContent = "Incoming Voice Call";
                    if (initialEl) initialEl.textContent = "?";

                    // Try to sync metadata immediately if we already have it from polling
                    syncIncomingCallMetadata();

                    incomingModal.show();

                    conn.on('cancel', () => {
                        incomingModal.hide();
                        activeConnection = null;
                        stopCallRingtone();
                    });
                });

                device.register();

            } catch (error) {
                console.error("Go Online Error:", error);
                goOffline();
            }
        }

        async function goOffline() {
            if (device) {
                device.destroy();
                device = null;
            }

            isOnline = false;

            try {
                await axios.post('{{ route("call.toggle-status") }}', { is_online: false });
            } catch (e) {
                console.error("Failed to update status in DB", e);
            }

            updateUIState(false);
        }

        function updateUIState(online) {
            const headerText = document.getElementById('call-status-text');
            if (online) {
                statusText.textContent = "Online";
                statusText.className = "text-success fw-bold";
                statusIcon.className = "fas fa-wifi fa-3x text-success mb-3";
                toggleBtn.className = "btn btn-danger w-100 rounded-pill py-2";
                toggleBtn.innerHTML = '<i class="fas fa-power-off me-2"></i> Go Offline';
                toggleBtn.disabled = false;

                if (headerIndicator) {
                    headerIndicator.classList.remove('text-muted');
                    headerIndicator.classList.add('text-success');
                    headerIndicator.querySelector('i').className = 'bi bi-telephone-fill';
                    if (headerText) headerText.textContent = 'Online';
                }
            } else {
                statusText.textContent = "Offline";
                statusText.className = "text-muted";
                statusIcon.className = "fas fa-phone-slash fa-3x text-muted mb-3";
                toggleBtn.className = "btn btn-primary w-100 rounded-pill py-2";
                toggleBtn.innerHTML = '<i class="fas fa-power-off me-2"></i> Go Online';
                toggleBtn.disabled = false;

                if (headerIndicator) {
                    headerIndicator.classList.remove('text-success');
                    headerIndicator.classList.add('text-muted');
                    headerIndicator.querySelector('i').className = 'bi bi-telephone-fill'; // Using fill as requested
                    if (headerText) headerText.textContent = 'Offline';
                }
            }
            // Dispatch event for other listeners (like dashboard settings)
            window.dispatchEvent(new CustomEvent('callStatusChanged', { detail: { isOnline: online } }));
        }

        acceptBtn.addEventListener('click', () => {
            if (activeConnection) {
                activeConnection.accept();
                stopCallRingtone();
                incomingModal.hide();
                inCallModal.show();
                startTimer();

                activeConnection.on('disconnect', () => {
                    inCallModal.hide();
                    stopTimer();
                    activeConnection = null;
                });
            }
        });

        rejectBtn.addEventListener('click', () => {
            if (activeConnection) {
                activeConnection.reject();
                stopCallRingtone();
                incomingModal.hide();
                activeConnection = null;
            }
        });

        hangupBtn.addEventListener('click', () => {
            if (activeConnection) {
                activeConnection.disconnect();
            }
        });

        function formatTime(totalSeconds) {
            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = (totalSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function startTimer() {
            seconds = 0;
            callTimer.textContent = "00:00";
            timerInterval = setInterval(() => {
                seconds++;
                callTimer.textContent = formatTime(seconds);
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
        }

        function stopCallRingtone() {
            if (callNotificationSound) {
                callNotificationSound.pause();
                callNotificationSound.currentTime = 0;
            }
        }
    });
</script>