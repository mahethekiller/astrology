@if(isset($data['data']))
    <div class="row g-4">
        <div class="col-12">
            <h5 class="fw-bold mb-4 text-primary d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                Birth Information
            </h5>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <div class="col">
                    <div class="p-3 rounded-4 border bg-white h-100 shadow-sm d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted mb-0">Date of Birth</div>
                            <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($request->date)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-4 border bg-white h-100 shadow-sm d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted mb-0">Time of Birth</div>
                            <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($request->time)->format('h:i A') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-4 border bg-white h-100 shadow-sm d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3 text-danger">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted mb-0">Location</div>
                            <div class="fw-bold small">{{ $request->latitude }}, {{ $request->longitude }}</div>
                            <div class="text-muted extra-small">{{ $data['data']['timezone'] ?? $request->timezone }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 rounded-4 border bg-white h-100 shadow-sm d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 text-warning">
                            <i class="bi bi-gear-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted mb-0">Ayanamsa</div>
                            <div class="fw-bold small">{{ $data['data']['ayanamsa_name'] ?? $request->ayanamsa }}</div>
                            <div class="text-muted extra-small">({{ number_format($data['data']['ayanamsa'] ?? 0, 4) }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($data['data']['nakshatra_details']))
            <div class="col-12 mt-2">
                <h5 class="fw-bold mb-4 text-primary d-flex align-items-center">
                    <i class="bi bi-stars me-2"></i>
                    Astro Profile
                </h5>
                <div class="table-responsive rounded-4 border bg-white shadow-sm overflow-hidden">
                    <table class="table table-hover mb-0 kundli-report-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-secondary small text-uppercase">Attribute</th>
                                <th class="px-4 py-3 text-secondary small text-uppercase">Value</th>
                                <th class="px-4 py-3 text-secondary small text-uppercase">Lord</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @php $n = $data['data']['nakshatra_details']; @endphp
                            <tr>
                                <td class="px-4 py-3 fw-medium">Nakshatra</td>
                                <td class="px-4 py-3 text-primary fw-bold">{{ $n['nakshatra']['name'] }}</td>
                                <td class="px-4 py-3 text-muted">{{ $n['nakshatra']['lord']['name'] }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 fw-medium">Chandra Rasi (Moon)</td>
                                <td class="px-4 py-3 text-primary fw-bold">{{ $n['chandra_rasi']['name'] }}</td>
                                <td class="px-4 py-3 text-muted">{{ $n['chandra_rasi']['lord']['name'] }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 fw-medium">Soorya Rasi (Sun)</td>
                                <td class="px-4 py-3 text-primary fw-bold">{{ $n['soorya_rasi']['name'] }}</td>
                                <td class="px-4 py-3 text-muted">{{ $n['soorya_rasi']['lord']['name'] }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 fw-medium">Zodiac (Western)</td>
                                <td class="px-4 py-3 text-primary fw-bold">{{ $n['zodiac']['name'] }}</td>
                                <td class="px-4 py-3 text-muted">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endif