<!-- Global Search -->
<div id="ep-search" class="api-section">
    <h4>Global Search Engine</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/search
    </div>
    <p class="text-muted mb-4">Aggregate searches across multiple schemas, like Astrologers and Blogs simultaneously.
    </p>

    <h6 class="fw-bold">Queries</h6>
    <table class="table table-sm table-bordered mt-2 mb-4">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>q</code></td>
                <td>The deep keyword target you are searching.</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "astrologers": [
        {
            "id": 1,
            "display_name": "Seer John"
        }
    ],
    "blogs": [
        {
            "id": 1,
            "title": "Zodiac Insights"
        }
    ]
}
}</pre>
</div>