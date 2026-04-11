<!-- Sliders -->
<div id="ep-sliders" class="api-section">
    <h4>Get Sliders / Banners</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/sliders
    </div>
    <p class="text-muted mb-4">Pull global marketing banners mapped for various endpoints such as app interfaces.</p>

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
                <td><code>group</code></td>
                <td>Filter grouping (example: `home`)</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": [
    {
        "id": 1,
        "title": "Welcome Promo",
        "group": "home",
        "image_url": "/storage/sliders/desktop.jpg",
        "mobile_image_url": "/storage/sliders/mobile.jpg",
        "app_image_url": "/storage/sliders/app.jpg"
    }
]
}</pre>
</div>