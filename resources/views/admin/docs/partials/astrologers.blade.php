<!-- Astrologers -->
<div id="ep-astrologers" class="api-section">
    <h4>Get Astrologers</h4>
    <div class="endpoint-badge mb-2">
        <span class="method method-get">GET</span> /api/astrologers
    </div>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/astrologers/{id}
    </div>
    <p class="text-muted mb-4">Paginated list or targeted lookup for confirmed Astrologers.</p>

    <h6 class="fw-bold">Queries (/api/astrologers)</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>search</code></td>
                <td>String query filtering by specific names</td>
            </tr>
            <tr>
                <td><code>specialization</code></td>
                <td>Filter slug (e.g. vedic-astrology)</td>
            </tr>
            <tr>
                <td><code>page</code></td>
                <td>List page index</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "display_name": "Seer John",
            "specializations": [...],
            "languages": [...],
            "ratings": [
                {
                    "id": 10,
                    "rating": 5,
                    "comment": "Incredible insight and very accurate reading!",
                    "user": {
                        "id": 5,
                        "name": "Jane Doe",
                        "profile_image": null
                    }
                }
            ]
        }
    ],
    "total": 5
}
}</pre>
</div>