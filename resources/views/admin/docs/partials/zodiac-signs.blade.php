<section id="ep-zodiac-signs" class="mb-5 pt-4">
    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
        <h4 class="text-primary fw-bold mb-0">Zodiac Signs</h4>
    </div>

    <!-- Get Zodiac Signs -->
    <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace fw-bold me-3">GET</span>
                <span class="fs-5 fw-semibold text-dark">/api/zodiac-signs</span>
            </div>
        </div>
        <div class="card-body bg-light-soft pt-0">
            <p class="text-muted mb-4 px-3">Retrieves a list of all active zodiac signs, their order, slugs, and
                associated icons. No authentication is required.</p>

            <h6 class="fw-bold mb-3 text-secondary ms-3">Response Example:</h6>
            <div class="bg-dark text-light p-3 rounded-3 font-monospace ms-3 me-3 opacity-75 shadow-inner"
                style="font-size: 0.85rem;">
                <pre class="m-0 text-success">
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Aries",
      "slug": "aries",
      "icon": "http://yourdomain.com/frontend/images/aries.png",
      "sort_order": 1
    },
    ...
  ]
}
</pre>
            </div>
        </div>
    </div>
</section>