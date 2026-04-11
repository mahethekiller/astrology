@extends('admin.layouts.app')


@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold text-primary mb-2">API Documentation</h3>
                <p class="text-muted">Interactive reference for Astroaura API endpoints.</p>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="api-sidebar pe-3">
                    <nav id="api-navbar" class="nav flex-column rounded shadow-sm bg-white p-3 border">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 px-3 mt-2" style="font-size: 0.75rem;">
                            Introduction</h6>
                        <a class="nav-link" href="#auth-intro">Authentication</a>

                        <h6 class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.75rem;">Auth
                            Endpoints</h6>
                        <a class="nav-link" href="#ep-register">Register</a>
                        <a class="nav-link" href="#ep-login">Login</a>
                        <a class="nav-link" href="#ep-logout">Logout</a>
                        <a class="nav-link" href="#ep-profile">Profile / User</a>
                        <a class="nav-link" href="#ep-wallet">Wallet Transactions</a>
                        <a class="nav-link" href="#ep-consultations">End Consultations</a>

                        <h6 class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.75rem;">Core
                            Features</h6>
                        <a class="nav-link" href="#ep-astrologers">Astrologers</a>
                        <a class="nav-link" href="#ep-blogs">Blogs</a>

                        <h6 class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.75rem;">Global &
                            Config</h6>
                        <a class="nav-link" href="#ep-sliders">Sliders</a>
                        <a class="nav-link" href="#ep-search">Global Search</a>
                    </nav>
                </div>
            </div>

            <!-- Documentation Content -->
            <div class="col-lg-9 col-md-8">
                <div data-bs-spy="scroll" data-bs-target="#api-navbar" data-bs-smooth-scroll="true" tabindex="0">

                    <!-- Partials Includes -->
                    @include('admin.docs.partials.auth-intro')
                    @include('admin.docs.partials.register')
                    @include('admin.docs.partials.login')
                    @include('admin.docs.partials.logout')
                    @include('admin.docs.partials.profile')
                    @include('admin.docs.partials.wallet')
                    @include('admin.docs.partials.consultations')
                    @include('admin.docs.partials.astrologers')
                    @include('admin.docs.partials.blogs')
                    @include('admin.docs.partials.sliders')
                    @include('admin.docs.partials.search')

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Smooth scrolling & active state for scrollspy is handled by Bootstrap data attributes automatically
            document.addEventListener('DOMContentLoaded', function () {
                const spy = new bootstrap.ScrollSpy(document.body, {
                    target: '#api-navbar',
                    offset: 100
                });
            });
        </script>
    @endpush
@endsection