<!-- Blogs -->
<div id="ep-blogs" class="api-section">
    <h4>Get Blogs</h4>
    <div class="endpoint-badge mb-2">
        <span class="method method-get">GET</span> /api/blogs
    </div>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/blogs/{slug}
    </div>
    <p class="text-muted mb-4">Retrieve articles or blog summaries systematically.</p>

    <h6 class="fw-bold">Queries (/api/blogs)</h6>
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
                <td>Filter by title or content</td>
            </tr>
            <tr>
                <td><code>category_id</code></td>
                <td>Filter entirely by a category ID constraint</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "title": "Welcome to Astrology",
            "slug": "welcome-to-astrology",
            "image_url": "/storage/blogs/image.jpg",
            "category": {
                "id": 2,
                "name": "Zodiac"
            }
        }
    ]
}
}</pre>
</div>