@extends('frontend.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ $astrologer->profile_image_url }}" alt="{{ $astrologer->display_name }}"
                                class="rounded-circle border border-3 border-primary shadow" width="120" height="120">
                            <h4 class="mt-3 fw-bold">{{ $astrologer->display_name }}</h4>
                            <p class="text-muted">Voice Call</p>
                        </div>

                        <!-- Call Status -->
                        <div id="call-status"
                            class="alert alert-info d-flex align-items-center justify-content-center gap-2">
                            <div class="spinner-grow spinner-grow-sm" role="status"></div>
                            <span>Initializing call...</span>
                        </div>

                        <!-- Timer -->
                        <div id="timer-container" class="d-none mb-4">
                            <h1 class="display-4 fw-bold text-success" id="call-timer">00:00</h1>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <button id="hangup-btn" class="btn btn-danger btn-lg rounded-pill shadow-sm" disabled>
                                <i class="fas fa-phone-slash me-2"></i> End Call
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@twilio/voice-sdk@2.11.1/dist/twilio.min.js"></script>
    <script>
        let device;
        let connection;
        let timerInterval;
        let seconds = 0;
        const astrologerId = "{{ $astrologer->id }}";
        console.log(astrologerId);
        const statusDiv = document.getElementById('call-status');
        const timerDisplay = document.getElementById('call-timer');
        const timerContainer = document.getElementById('timer-container');
        const hangupBtn = document.getElementById('hangup-btn');
        const statusText = statusDiv.querySelector('span');

        function updateStatus(msg, type = 'info') {
            statusText.textContent = msg;
            statusDiv.className = `alert alert-${type} d-flex align-items-center justify-content-center gap-2`;
            if (type !== 'info') {
                statusDiv.querySelector('.spinner-grow')?.remove();
            }
        }

        function formatTime(totalSeconds) {
            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = (totalSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function startTimer() {
            timerContainer.classList.remove('d-none');
            timerInterval = setInterval(() => {
                seconds++;
                timerDisplay.textContent = formatTime(seconds);
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
        }

        async function initCall() {
            try {
                // 1. Get Token
                const response = await axios.post('{{ route("call.token") }}');
                const token = response.data.token;

                // 2. Setup Device
                device = new Twilio.Device(token, {
                    codecPreferences: ['opus', 'pcmu'],
                    fakeLocalDTMF: true,
                    enableRingingState: true
                });

                // device.on('ready', function (device) {
                // v2: No ready event.
                // });

                // Register (optional for outgoing but good practice) and Connect
                // For immediate call:
                console.log("Connecting...");
                updateStatus('Calling...', 'primary');

                const params = {
                    astrologer_id: astrologerId
                };

                // device.connect() returns a Promise resolving to Call object
                connection = await device.connect({ params: params });
                setupConnectionListeners(connection);

                device.on('error', function (error) {
                    console.error('Twilio Error:', error);
                    updateStatus('Call Application Error: ' + error.message, 'danger');
                });

            } catch (error) {
                console.error(error);
                updateStatus('Failed to initialize call.', 'danger');
            }
        }

        function setupConnectionListeners(conn) {
            hangupBtn.disabled = false;

            conn.on('accept', function () {
                updateStatus('Connected', 'success');
                debugger
                startTimer();
            });

            conn.on('disconnect', function () {
                updateStatus('Call Ended', 'secondary');
                stopTimer();
                hangupBtn.disabled = true;
                setTimeout(() => {
                    window.location.href = "{{ route('astrologer.show', $astrologer->id) }}";
                }, 2000);
            });

            conn.on('cancel', function () {
                updateStatus('Call Cancelled', 'warning');
                stopTimer();
            });

            conn.on('reject', function () {
                updateStatus('Call Rejected/Busy', 'warning');
                stopTimer();
            });

            conn.on('error', function (error) {
                console.error("Connection Error", error);
                updateStatus('Connection Error', 'danger');
            });
        }

        hangupBtn.addEventListener('click', function () {
            if (device) {
                device.disconnectAll();
            }
        });

        // Start
        if (typeof Twilio !== 'undefined') {
            initCall();
        } else {
            console.error('Twilio SDK not loaded.');
            updateStatus('Failed to load Twilio SDK. Please refresh.', 'danger');
        }

    </script>
@endpush