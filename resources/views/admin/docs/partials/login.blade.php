<!-- Login -->
<div id="ep-login" class="api-section">
    <h4>Log in</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/login
    </div>
    <p class="text-muted mb-4">Login using email and password to retrieve an ecosystem access token.</p>

    <h6 class="fw-bold">Body Parameters</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>email</code></td>
                <td>string</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td><code>password</code></td>
                <td>string</td>
                <td>Yes</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Logged in successfully",
"data": {
    "user": { ... },
    "access_token": "2|XyZ123...",
    "token_type": "Bearer"
}
}</pre>
</div>