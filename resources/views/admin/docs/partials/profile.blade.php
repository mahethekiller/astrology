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

    <hr class="my-5 border-light">

    <h4>Update Profile</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/profile/update
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
        <span class="badge bg-info text-dark border border-info px-2 py-1 ms-1"><i class="bi bi-file-earmark-image me-1"></i> Supports FormData</span>
    </div>
    <p class="mb-4">Update the authenticated user's profile information, including their profile image.</p>

    <h6 class="fw-bold">Body Parameters (FormData)</h6>
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
                <td>No</td>
                <td>Full name of the user</td>
            </tr>
            <tr>
                <td><code>email</code></td>
                <td>string</td>
                <td>No</td>
                <td>Valid, unique email address</td>
            </tr>
            <tr>
                <td><code>phone_number</code></td>
                <td>string</td>
                <td>No</td>
                <td>Valid, unique phone number</td>
            </tr>
            <tr>
                <td><code>bio</code></td>
                <td>string</td>
                <td>No</td>
                <td>Short biography or description</td>
            </tr>
            <tr>
                <td><code>profile_image</code></td>
                <td>file</td>
                <td>No</td>
                <td>Image file (jpeg, png, jpg, gif) max 2MB</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Profile updated successfully",
"data": {
    "user": {
        "id": 1,
        "name": "Jane Doe Updated",
        "email": "jane@example.com",
        "phone_number": "1234567890",
        "bio": "I love astrology!",
        "profile_image": "170000000_image.jpg"
    }
}
}</pre>
</div>