<!-- Wallet Transactions -->
<div id="ep-wallet" class="api-section">
    <h4>Check Wallet Balance</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/wallet/balance
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i> Requires Authentication</span>
    </div>
    <p class="mb-4">Lightweight endpoint explicitly providing the verified user's currently available fiat balance
        securely.</p>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "wallet_balance": "150.00"
}
}</pre>

    <hr class="my-5 border-light">

    <!-- Wallet Transactions -->
    <h4>Wallet Transactions</h4>
    <div class="endpoint-badge">
        <span class="method method-get">GET</span> /api/wallet/transactions
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i> Requires Authentication</span>
    </div>
    <p class="mb-4">Fetch historical wallet transactions tied to the securely authenticated user.</p>

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
                <td><code>type</code></td>
                <td>Filter exclusively by <code>credit</code> or <code>debit</code>.</td>
            </tr>
            <tr>
                <td><code>page</code></td>
                <td>List page index</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"data": {
    "wallet_balance": "150.00",
    "transactions": {
        "current_page": 1,
        "data": [
            {
                "id": 5,
                "amount": "50.00",
                "type": "credit",
                "description": "Admin recharge",
                "created_at": "2026-03-12T10:15:00.000000Z"
            }
        ],
        "total": 1
    }
}
}</pre>

    <hr class="my-5 border-light">

    <!-- Add Funds -->
    <h4>Add Funds to Wallet</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/wallet/add
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i> Requires Authentication</span>
    </div>
    <p class="mb-4">Credits a specific amount into the authenticated user's digital wallet balance.</p>

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
                <td><code>amount</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Amount exceeding 1.00</td>
            </tr>
            <tr>
                <td><code>description</code></td>
                <td>string</td>
                <td>No</td>
                <td>Label for history logging</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Funds added successfully",
"data": {
    "wallet_balance": "200.00"
}
}</pre>

    <hr class="my-5 border-light">

    <!-- Deduct Funds -->
    <h4>Deduct Funds from Wallet</h4>
    <div class="endpoint-badge">
        <span class="method method-post">POST</span> /api/wallet/deduct
    </div>
    <div class="mb-4 mt-n2">
        <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-lock-fill me-1"></i> Requires Authentication</span>
    </div>
    <p class="mb-4">Deducts a specific amount from the authenticated user's digital wallet balance conditionally on
        availability.</p>

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
                <td><code>amount</code></td>
                <td>numeric</td>
                <td>Yes</td>
                <td>Amount exceeding 1.00</td>
            </tr>
            <tr>
                <td><code>description</code></td>
                <td>string</td>
                <td>No</td>
                <td>Label for history logging</td>
            </tr>
        </tbody>
    </table>

    <h6 class="fw-bold">Response Example (200 OK)</h6>
    <pre class="api-code">{
"status": "success",
"message": "Funds deducted successfully",
"data": {
    "wallet_balance": "150.00"
}
}</pre>

</div>