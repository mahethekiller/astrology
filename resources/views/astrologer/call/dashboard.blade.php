@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Call Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Receive Calls</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body text-center p-5">
                <div id="status-container" class="mb-4">
                    <i class="fas fa-phone-slash fa-4x text-muted mb-3" id="status-icon"></i>
                    <h3 id="status-text">You are Offline</h3>
                    <p class="text-muted">You must be "Online" on this page to receive calls.</p>
                </div>

                <button id="toggle-online-btn" class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="fas fa-power-off me-2"></i> Go Online
                </button>
            </div>
        </div>
    </div>

    <!-- Incoming Call Modal -->
    <div class="modal fade" id="incomingCallModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="spinner-grow text-success" style="width: 3rem; height: 3rem;" role="status"></div>
                    </div>
                    <h4>Incoming Call...</h4>
                    <p>A user is calling you.</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" class="btn btn-danger rounded-circle p-3" id="reject-btn">
                            <i class="fas fa-phone-slash fa-2x"></i>
                        </button>
                        <button type="button" class="btn btn-success rounded-circle p-3" id="accept-btn">
                            <i class="fas fa-phone fa-2x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- In Call Modal/Overlay -->
    <div class="modal fade" id="inCallModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4 bg-dark text-white">
                <div class="modal-body">
                    <h4 class="text-success">Connected</h4>
                    <h1 class="display-4 fw-bold my-4" id="call-timer">00:00</h1>
                    <button type="button" class="btn btn-danger btn-lg rounded-pill px-5" id="hangup-btn">
                        <i class="fas fa-phone-slash me-2"></i> End Call
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@twilio/voice-sdk@2.11.1/dist/twilio.min.js"></script>
    <script>
        let device;
        let activeConnection;
        let timerInterval;
        let seconds = 0;
        const toggleBtn = document.getElementById('toggle-online-btn');
        const statusText = document.getElementById('status-text');
        const statusIcon = document.getElementById('status-icon');

        // Modals
        const incomingModal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
        const inCallModal = new bootstrap.Modal(document.getElementById('inCallModal'));

        // Buttons
        const acceptBtn = document.getElementById('accept-btn');
        const rejectBtn = document.getElementById('reject-btn');
        const hangupBtn = document.getElementById('hangup-btn');
        const callTimer = document.getElementById('call-timer');

        let isOnline = false;

        toggleBtn.addEventListener('click', async () => {
            if (!isOnline) {
                await goOnline();
            } else {
                goOffline();
            }
        });

        async function goOnline() {
            try {
                toggleBtn.disabled = true;
                toggleBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Connecting...';
                console.log("Requesting token...");

                const response = await axios.post('{{ route("call.token") }}', { is_astrologer: true });
                console.log("Token received:", response.data);
                const token = response.data.token;

                console.log("Initializing Twilio Device...");
                device = new Twilio.Device(token, {
                    codecPreferences: ['opus', 'pcmu'],
                    fakeLocalDTMF: true,
                    enableRingingState: true,
                    debug: true
                });

                device.on('ready', function () {
                    console.log("Twilio Device Ready!");
                    isOnline = true;
                    updateUIState(true);
                });
                
                device.on('registered', function () {
                    console.log("Twilio Device Registered!");
                });

                device.on('error', function (error) {
                    console.error("Twilio Device Error:", error);
                    alert("Twilio Error: " + error.message);
                    goOffline();
                });

                device.on('incoming', function (conn) {
                    console.log("Incoming connection from " + conn.parameters.From);
                    activeConnection = conn;
                    incomingModal.show();

                    // Handle caller hanging up before accept
                    conn.on('cancel', () => {
                        incomingModal.hide();
                        activeConnection = null;
                    });
                });
                
                // Explicitly register if needed (though constructor with token should do it)
                // device.register(); 

            } catch (error) {
                console.error("Go Online Error:", error);
                alert("Failed to go online. See console for details.");
                goOffline();
            }
        }

        // Check SDK
        if (typeof Twilio === 'undefined') {
            alert('Twilio SDK failed to load. Please refresh the page.');
            console.error('Twilio SDK not loaded.');
        }

        function goOffline() {
            if (device) {
                device.destroy(); // or disconnectAll
                device = null;
            }
            isOnline = false;
            updateUIState(false);
        }

        function updateUIState(online) {
            if (online) {
                statusText.textContent = "You are Online";
                statusText.className = "text-success";
                statusIcon.className = "fas fa-wifi fa-4x text-success mb-3";
                toggleBtn.className = "btn btn-danger btn-lg rounded-pill px-5";
                toggleBtn.innerHTML = '<i class="fas fa-power-off me-2"></i> Go Offline';
                toggleBtn.disabled = false;
            } else {
                statusText.textContent = "You are Offline";
                statusText.className = "text-muted";
                statusIcon.className = "fas fa-phone-slash fa-4x text-muted mb-3";
                toggleBtn.className = "btn btn-primary btn-lg rounded-pill px-5";
                toggleBtn.innerHTML = '<i class="fas fa-power-off me-2"></i> Go Online';
                toggleBtn.disabled = false;
            }
        }

        // Call Handling
        acceptBtn.addEventListener('click', () => {
            if (activeConnection) {
                activeConnection.accept();
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

    </script>
@endpush