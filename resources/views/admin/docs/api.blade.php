@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">API Documentation</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Welcome to the Astroaura API documentation. Use these endpoints to integrate
                            authentication and user features into your mobile or web applications.</p>

                        <div class="alert alert-info border-left-info shadow h-100 py-2 mb-4">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Authentication
                                        </div>
                                        <div class="text-gray-800">
                                            All protected routes require a <code>Bearer Token</code> in the
                                            <code>Authorization</code> header.
                                            <br>
                                            <code>Authorization: Bearer YOUR_TOKEN_HERE</code>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-info-circle h2 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion" id="apiAccordion">
                            <!-- Register API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingRegister">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseRegister" aria-expanded="false"
                                        aria-controls="collapseRegister">
                                        <span class="badge bg-success me-2">POST</span> /api/register
                                    </button>
                                </h2>
                                <div id="collapseRegister" class="accordion-collapse collapse"
                                    aria-labelledby="headingRegister" data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Register a new user account and receive an access token.</p>

                                        <h6>Request Parameters</h6>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
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
                                                    <td>Full name of the user</td>
                                                </tr>
                                                <tr>
                                                    <td><code>email</code></td>
                                                    <td>string</td>
                                                    <td>Yes</td>
                                                    <td>Unique email address</td>
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
                                                    <td>Minimum 8 characters</td>
                                                </tr>
                                                <tr>
                                                    <td><code>password_confirmation</code></td>
                                                    <td>string</td>
                                                    <td>Yes</td>
                                                    <td>Must match password</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <h6>Example Response (201 Created)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
            "status": "success",
            "message": "User registered successfully",
            "data": {
                "user": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "john@example.com",
                    "phone_number": "1234567890",
                    "created_at": "2024-03-20T10:00:00.000000Z"
                },
                "access_token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz",
                "token_type": "Bearer"
            }
        }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Login API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingLogin">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseLogin" aria-expanded="false"
                                        aria-controls="collapseLogin">
                                        <span class="badge bg-primary me-2">POST</span> /api/login
                                    </button>
                                </h2>
                                <div id="collapseLogin" class="accordion-collapse collapse" aria-labelledby="headingLogin"
                                    data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Log in with email and password to receive an access token.</p>

                                        <h6>Request Parameters</h6>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Type</th>
                                                    <th>Required</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><code>email</code></td>
                                                    <td>string</td>
                                                    <td>Yes</td>
                                                    <td>User email address</td>
                                                </tr>
                                                <tr>
                                                    <td><code>password</code></td>
                                                    <td>string</td>
                                                    <td>Yes</td>
                                                    <td>User password</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
            "status": "success",
            "message": "Logged in successfully",
            "data": {
                "user": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "john@example.com",
                    "phone_number": "1234567890"
                },
                "access_token": "2|XyZ123...",
                "token_type": "Bearer"
            }
        }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Logout API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingLogout">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseLogout" aria-expanded="false"
                                        aria-controls="collapseLogout">
                                        <span class="badge bg-danger me-2">POST</span> /api/logout
                                    </button>
                                </h2>
                                <div id="collapseLogout" class="accordion-collapse collapse" aria-labelledby="headingLogout"
                                    data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Revoke the current access token. Requires authentication.</p>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
            "status": "success",
            "message": "Logged out successfully"
        }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Get User PROFILE API (Detailed) -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingProfile">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseProfile" aria-expanded="false"
                                        aria-controls="collapseProfile">
                                        <span class="badge bg-secondary me-2">GET</span> /api/profile
                                    </button>
                                </h2>
                                <div id="collapseProfile" class="accordion-collapse collapse"
                                    aria-labelledby="headingProfile" data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Get detailed user profile including wallet balance. Requires authentication.</p>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
        "status": "success",
        "data": {
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone_number": "1234567890",
                "wallet": {
                    "id": 1,
                    "user_id": 1,
                    "balance": "100.00"
                }
            },
            "wallet_balance": "100.00"
        }
    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Get Astrologers API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingAstrologers">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseAstrologers"
                                        aria-expanded="false" aria-controls="collapseAstrologers">
                                        <span class="badge bg-success me-2">GET</span> /api/astrologers
                                    </button>
                                </h2>
                                <div id="collapseAstrologers" class="accordion-collapse collapse"
                                    aria-labelledby="headingAstrologers" data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Get a paginated list of active and approved astrologers.</p>

                                        <h6>Query Parameters</h6>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Type</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><code>search</code></td>
                                                    <td>string</td>
                                                    <td>Search by name or about</td>
                                                </tr>
                                                <tr>
                                                    <td><code>specialization</code></td>
                                                    <td>string</td>
                                                    <td>Filter by specialization slug (e.g., Vedic Astrology)</td>
                                                </tr>
                                                <tr>
                                                    <td><code>page</code></td>
                                                    <td>integer</td>
                                                    <td>Page number for pagination</td>
                                                </tr>
                                                <tr>
                                                    <td><code>limit</code></td>
                                                    <td>integer</td>
                                                    <td>Number of items per page (default: 12)</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
        "status": "success",
        "data": {
            "current_page": 1,
            "data": [
                {
                    "id": 1,
                    "display_name": "Astrologer 1",
                    "slug": "astrologer-1",
                    "experience_years": 10,
                    "rating": "4.50",
                    "specializations": [...],
                    "languages": [...]
                }
            ],
            "total": 50
        }
    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Get Astrologer Detail API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingAstrologerDetail">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseAstrologerDetail"
                                        aria-expanded="false" aria-controls="collapseAstrologerDetail">
                                        <span class="badge bg-info me-2 text-white">GET</span> /api/astrologers/{id}
                                    </button>
                                </h2>
                                <div id="collapseAstrologerDetail" class="accordion-collapse collapse"
                                    aria-labelledby="headingAstrologerDetail" data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Get detailed information for a specific astrologer by ID.</p>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
        "status": "success",
        "data": {
            "id": 1,
            "display_name": "Astrologer 1",
            "about": "Expert in Vedic Astrology...",
            "experience_years": 10,
            "chat_price": "10.00",
            "call_price": "15.00",
            "specializations": [...],
            "languages": [...]
        }
    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Get User PROFILE API -->
                            <div class="accordion-item shadow-sm mb-3 border-0">
                                <h2 class="accordion-header" id="headingUser">
                                    <button class="accordion-button collapsed bg-white text-dark" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="false"
                                        aria-controls="collapseUser">
                                        <span class="badge bg-secondary me-2">GET</span> /api/user
                                    </button>
                                </h2>
                                <div id="collapseUser" class="accordion-collapse collapse" aria-labelledby="headingUser"
                                    data-bs-parent="#apiAccordion">
                                    <div class="accordion-body">
                                        <h6>Description</h6>
                                        <p>Get the authenticated user's profile details.</p>

                                        <h6>Example Response (200 OK)</h6>
                                        <pre class="bg-light p-3 border rounded"><code>{
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone_number": "1234567890",
            "created_at": "2024-03-20T10:00:00.000000Z",
            "updated_at": "2024-03-20T10:00:00.000000Z"
        }</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection