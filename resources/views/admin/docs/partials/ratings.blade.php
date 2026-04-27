<!-- Ratings -->
<div id="ep-ratings" class="api-section">
    <h4>Get Astrologer Ratings</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/ratings
    </div>
    <p class="mb-4">Fetch paginated public ratings and reviews for a specific astrologer profile.</p>

    <h6 class="fw-bold">Query Parameters</h6>
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
                <td><code>astrologer_profile_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Primary ID of the Astrologer's profile</td>
            </tr>
            <tr>
                <td><code>per_page</code></td>
                <td>numeric</td>
                <td>No</td>
                <td>Items per page (default: 15)</td>
            </tr>
            <tr>
                <td><code>page</code></td>
                <td>numeric</td>
                <td>No</td>
                <td>Page number for pagination</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"success": true,
"data": {
    "current_page": 1,
    "data": [
        {
            "id": 45,
            "user_id": 2,
            "astrologer_profile_id": 1,
            "rating": 5,
            "comment": "Excellent consultation!",
            "ratable_type": "App\\Models\\ChatRequest",
            "ratable_id": 123,
            "status": "approved",
            "created_at": "2026-04-12T10:15:00.000000Z",
            "updated_at": "2026-04-12T10:15:00.000000Z",
            "user": {
                "id": 2,
                "name": "John Doe",
                "image": "profile.jpg"
            }
        }
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 1,
    "last_page_url": "...",
    "links": [...],
    "next_page_url": null,
    "path": "...",
    "per_page": 15,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
}</pre>

    <hr class="my-5 border-light">

    <h4>Add Astrologer Rating</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/ratings
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i>
            Requires Authentication</span>
    </div>
    <p class="mb-4">Submit a rating out of 5 stars for a completed chat or call consultation. Ratings are cached
        directly into the Astrologer's profile metrics.</p>

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
                <td><code>astrologer_profile_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Primary ID of the Astrologer's profile</td>
            </tr>
            <tr>
                <td><code>rating</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Score from 1 to 5</td>
            </tr>
            <tr>
                <td><code>comment</code></td>
                <td>string</td>
                <td>No</td>
                <td>Optional feedback comment (max 500 chars)</td>
            </tr>
            <tr>
                <td><code>ratable_type</code></td>
                <td>string</td>
                <td>Yes</td>
                <td>String value: <code>ChatRequest</code> or <code>CallRequest</code></td>
            </tr>
            <tr>
                <td><code>ratable_id</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Primary ID of the specific Chat or Call Request</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"success": true,
"message": "Thank you for your rating!",
"data": {
    "id": 45,
    "user_id": 2,
    "astrologer_profile_id": 1,
    "rating": 5,
    "comment": "Excellent consultation!",
    "ratable_type": "App\\Models\\ChatRequest",
    "ratable_id": 123,
    "status": "approved",
    "created_at": "2026-04-12T10:15:00.000000Z",
    "updated_at": "2026-04-12T10:15:00.000000Z"
}
}</pre>
</div>