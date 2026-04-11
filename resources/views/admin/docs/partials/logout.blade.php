<!-- Logout -->
<div id="ep-logout" class="api-section">
    <h4>Logout</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/logout
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Revokes and deletes the current access token.</p>

    <h6 class="fw-bold">Response (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Logged out successfully"
}</pre>
</div>