@if(isset($data['data']['dasha_periods']))
    @php
        $dashas = $data['data']['dasha_periods'];
    @endphp
    <div class="accordion" id="dashaAccordion">
        @foreach($dashas as $dashaKey => $dasha)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $dashaKey }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $dashaKey }}" aria-expanded="false" aria-controls="collapse{{ $dashaKey }}">
                        <strong>{{ $dasha['name'] }}</strong> &nbsp;
                        <span class="text-muted small">({{ \Carbon\Carbon::parse($dasha['start'])->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($dasha['end'])->format('d M Y') }})</span>
                    </button>
                </h2>
                <div id="collapse{{ $dashaKey }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $dashaKey }}"
                    data-bs-parent="#dashaAccordion">
                    <div class="accordion-body p-2">
                        {{-- Antardasha --}}
                        @if(isset($dasha['antardasha']))
                            <div class="accordion" id="antardashaAccordion{{ $dashaKey }}">
                                @foreach($dasha['antardasha'] as $antarKey => $antar)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingAntar{{ $dashaKey }}_{{ $antarKey }}">
                                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseAntar{{ $dashaKey }}_{{ $antarKey }}" aria-expanded="false"
                                                aria-controls="collapseAntar{{ $dashaKey }}_{{ $antarKey }}">
                                                {{ $antar['name'] }} &nbsp;
                                                <span
                                                    class="text-muted small">({{ \Carbon\Carbon::parse($antar['start'])->format('d M Y') }}
                                                    - {{ \Carbon\Carbon::parse($antar['end'])->format('d M Y') }})</span>
                                            </button>
                                        </h2>
                                        <div id="collapseAntar{{ $dashaKey }}_{{ $antarKey }}" class="accordion-collapse collapse"
                                            aria-labelledby="headingAntar{{ $dashaKey }}_{{ $antarKey }}"
                                            data-bs-parent="#antardashaAccordion{{ $dashaKey }}">
                                            <div class="accordion-body p-0">
                                                {{-- Pratyantardasha Table --}}
                                                @if(isset($antar['pratyantardasha']))
                                                    <div class="table-responsive">
                                                        <table class="table kundli-report-table table-sm table-bordered mb-0 small">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Pratyantardasha</th>
                                                                    <th>Start</th>
                                                                    <th>End</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($antar['pratyantardasha'] as $prat)
                                                                    <tr>
                                                                        <td>{{ $prat['name'] }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($prat['start'])->format('d M Y, h:i A') }}
                                                                        </td>
                                                                        <td>{{ \Carbon\Carbon::parse($prat['end'])->format('d M Y, h:i A') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No Antardasha data details.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">No Dasha Periods data available.</div>
@endif