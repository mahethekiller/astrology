@extends('frontend.layouts.app')

@section('title', 'Our Astrologers')

@section('content')


    <!-- Breadcrumb -->
    <div class="container ">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb customBred">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Our Astrologers</a></li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb -->


    <div class=" ">
        <div class="container ">
            <h2 class="title2 ">OUR ASTROLOGERS</h2>
            <div class="headingDeign"><img src="{{ asset('images/headingDesign.png') }}" /></div>

            <!-- Specialization Tabs -->
            <ul class="nav nav-tabs flex-nowrap tab-scroll-x" role="tablist" id="specializationTabs">
                <li class="nav-item">
                    <button class="nav-link active" data-slug="" onclick="filterBySpecialization(this, '')">
                        <img src="{{ asset('images/grid.png') }}" /> ALL
                    </button>
                </li>
                @foreach($specializations as $specialization)
                    <li class="nav-item">
                        <button class="nav-link" data-slug="{{ $specialization->slug }}"
                            onclick="filterBySpecialization(this, '{{ $specialization->slug }}')">
                            {{-- Placeholder icon or dynamic if available --}}
                            <img src="{{ asset('images/heart.png') }}" /> {{ strtoupper($specialization->name) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Search Box -->
            <div class="search-box w-100 mt-3 mb-4">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Astrologers..."
                        onkeyup="debounceSearch()" />
                    <button type="button" class="btn btn-warning" onclick="fetchAstrologers()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <!-- Astrologer List Container -->
            <div id="astrologer-data-container" class="mt-4">
                @include('frontend.astrologer.partials.astrologer-list', ['astrologers' => $astrologers])
            </div>

            <!-- Loader -->
            <div id="loader" class="text-center d-none my-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentSpecialization = '';
        let searchTimeout;

        function filterBySpecialization(element, slug) {
            currentSpecialization = slug;

            // Update active tab visual
            document.querySelectorAll('#specializationTabs .nav-link').forEach(btn => {
                btn.classList.remove('active');
            });
            element.classList.add('active');

            fetchAstrologers();
        }

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchAstrologers();
            }, 500);
        }

        function fetchAstrologers(url = null) {
            const searchQuery = document.getElementById('searchInput').value;
            const container = document.getElementById('astrologer-data-container');
            const loader = document.getElementById('loader');

            // Show loader, hide container opacity
            loader.classList.remove('d-none');
            container.style.opacity = '0.5';

            // Build URL
            let fetchUrl = url || "{{ route('astrologer.index') }}";

            // Append params
            const urlObj = new URL(fetchUrl);
            urlObj.searchParams.set('specialization', currentSpecialization);
            if (searchQuery) {
                urlObj.searchParams.set('search', searchQuery);
            } else {
                urlObj.searchParams.delete('search');
            }

            // Fetch Data
            fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    container.style.opacity = '1';
                    loader.classList.add('d-none');
                })
                .catch(error => {
                    console.error('Error:', error);
                    loader.classList.add('d-none');
                    container.style.opacity = '1';
                });
        }

        // Handle Pagination Clicks via Event Delegation
        document.addEventListener('click', function (e) {
            const link = e.target.closest('.pagination .page-link');
            if (link && link.href) {
                e.preventDefault();
                fetchAstrologers(link.href);
            }
        });
    </script>
@endpush