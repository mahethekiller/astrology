<!-- Profile -->
<div id="ep-profile" class="api-section">
    <h4>Profile Data</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/profile
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Retrieve details about the securely signed in User (wallet included).</p>

    <h6 class="fw-bold">Response (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "user": {
        "id": 1,
        "name": "Jane Doe",
        "email": "jane@example.com",
        ...
    },
    "wallet_balance": "150.00"
}
}</pre>
</div>