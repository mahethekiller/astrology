<!-- Registration -->
<div id="ep-register" class="api-section">
    <h4>Register User</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/register
    </div>
    <p class="text-muted mb-4">Register a new user account and obtain an access token.</p>

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
                <td><code>name</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>Full user name</td>
            </tr>
            <tr>
                <td><code>email</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>Unique email Address</td>
            </tr>
            <tr>
                <td><code>phone_number</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>Unique phone number</td>
            </tr>
            <tr>
                <td><code>password</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>Minimum 8 chars</td>
            </tr>
            <tr>
                <td><code>password_confirmation</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>Must match password</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response (201 Created)</h6>
    <pre class="api-code">{
"status": "success",
"message": "User registered successfully",
"data": {
    "user": { ... },
    "access_token": "1|AbCdEfGhIj...",
    "token_type": "Bearer"
}
}</pre>
</div>