<!-- Consultations -->
<div id="ep-consultations" class="api-section">
    <div class="alert alert-info border border-info-subtle bg-info-subtle text-info-emphasis mb-5 shadow-sm">
        <h5 class="alert-heading fw-bold mb-3"><i class="bi bi-info-circle-fill me-2"></i> Integration Flow (Twilio +
            Astroaura)</h5>
        <p class="mb-2">Your mobile app will interact directly with <strong>Twilio's SDKs</strong> for the raw
            audio/text layer, but it must use these APIs symmetrically to ensure wallets and chat logs are tracked
            accurately on our servers:</p>
        <ol class="mb-0">
            <li class="mb-1"><strong>Pre-Check:</strong> Call <code>/api/wallet/balance</code> to verify user has
                adequate funds before unlocking the dialer.</li>
            <li class="mb-1"><strong>Initiate DB Log:</strong> Trigger <code>/api/consultations/request-chat</code> (or
                call) to actively log an ongoing session locally in our backend natively. The API returns an active
                Request ID.</li>
            <li class="mb-1"><strong>Start Action:</strong> App launches the Twilio SDK utilizing their native
                connections. A stopwatch starts ticking locally inside the app.</li>
            <li class="mb-1"><strong>End Interaction:</strong> On hangup, take the total elapsed stopwatch minutes and push the exact
                duration into <code>/api/consultations/end-chat</code> (or end-call) using your Request ID to formally
                deduct wallet funds natively and close the session!</li>
            <li><strong>Automated Cleanup (Fail-safe):</strong> If an active chat or call disconnects and the app fails to send the <code>end-chat</code> or <code>end-call</code> API requests, the system automatically uses a Twilio Voice Webhook and a background scheduled task (Cron Job running every minute) to finalize the session and securely process the wallet transaction automatically.</li>
        </ol>
    </div>

    <h4>Initiate Chat Request</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/consultations/request-chat
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Logs a new Chat Session explicitly into the database. Your mobile app can continue dealing
        synchronously with explicit Twilio Chat APIs using the active status natively.</p>

    <h6 class="fw-bold">Body Parameters</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>astrologer_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Target Astrologer to queue</td>
            </tr>
            <tr>
                <td><code>twilio_sid</code></td>
                <td>string</td>
                <td>No</td>
                <td>The Session ID from Twilio (if generated immediately)</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Chat request successfully logged.",
"data": {
    "chat_request_id": 142,
    "status": "active"
}
}</pre>

    <hr class="my-5 border-light">

    <h4>Check Chat Status</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/consultations/chat-status/{id}
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Poll this endpoint to check if the astrologer has accepted or rejected the chat request.</p>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "chat_request_id": 142,
    "status": "accepted",
    "twilio_sid": "CHXXXXXXXXXXXXXXXXX"
}
}</pre>

    <hr class="my-5 border-light">

    <h4>Initiate Voice Call Request</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/consultations/request-call
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Logs a new Call Session assigning an automatic <code>start_time</code> tracking index for
        calculations.</p>

    <h6 class="fw-bold">Body Parameters</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>astrologer_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Target Astrologer to dial</td>
            </tr>
            <tr>
                <td><code>twilio_sid</code></td>
                <td>string</td>
                <td>No</td>
                <td>The Twilio Call Session ID</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Call request successfully logged.",
"data": {
    "call_request_id": 98,
    "call_status": "active",
    "start_time": "2026-04-12T10:00:00.000000Z"
}
}</pre>

    <hr class="my-5 border-light">

    <h4>Check Call Status</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/consultations/call-status/{id}
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Poll this endpoint to check if the astrologer has accepted or rejected the call request.</p>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "call_request_id": 98,
    "status": "accepted",
    "twilio_sid": "CAXXXXXXXXXXXXXXXXX"
}
}</pre>

    <hr class="my-5 border-light">
    <h4>Complete Chat Consultations</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/consultations/end-chat
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Finalizes an active chat request, mathematically computes costs referencing the Astrologer's current
        pricing metrics, updates double-wallet ledgers (charging users, directly paying Astrologers commissions via
        Wallet records), and closes the session natively.</p>

    <h6 class="fw-bold">Body Parameters</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>chat_request_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Primary ID of the active ChatRequest</td>
            </tr>
            <tr>
                <td><code>duration_minutes</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Total chat duration in valid minutes</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Chat consultation completed successfully.",
"data": {
    "duration": 15,
    "total_cost": 300,
    "user_remaining_balance": "85.00"
}
}</pre>

    <hr class="my-5 border-light">

    <!-- Complete Call -->
    <h4>Complete Voice Call Consultations</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/consultations/end-call
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Identical ledger calculations executing on Voice Call schemas.</p>

    <h6 class="fw-bold">Body Parameters</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>call_request_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Primary ID of the active CallRequest</td>
            </tr>
            <tr>
                <td><code>duration_minutes</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Total voice call duration mapped</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Call consultation completed successfully.",
"data": {
    "duration": 45,
    "total_cost": 900,
    "user_remaining_balance": "14.00"
}
}</pre>

</div>