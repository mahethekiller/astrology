@php
    $chandrashtama = $data['data']['chandrashtama'] ?? [];
    $timings = $data['data']['chandrashtama_timing'] ?? [];
@endphp

<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Chandrashtama Periods (Janma Rasi: {{ $chandrashtama['rasi']['name'] ?? 'Unknown' }})</h5>
    </div>
    <div class="card-body p-0">
        @if(!empty($timings))
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 text-center small">
                    <thead class="table-dark">
                        <tr>
                            <th>Period Start</th>
                            <th>Period End</th>
                            <th>Nakshatra Timings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timings as $period)
                            <tr>
                                <td class="align-middle fw-bold">
                                    {{ \Carbon\Carbon::parse($period['start'])->format('d M Y, h:i A') }}</td>
                                <td class="align-middle fw-bold">
                                    {{ \Carbon\Carbon::parse($period['end'])->format('d M Y, h:i A') }}</td>
                                <td class="text-start p-0">
                                    <table class="table table-sm table-borderless mb-0">
                                        @foreach($period['nakshatra_timings'] as $nt)
                                            <tr class="{{ $nt['is_peak'] ? 'table-danger' : '' }}">
                                                <td style="width: 30%">
                                                    {{ $nt['nakshatra']['name'] }}
                                                    @if($nt['is_peak']) <span class="badge bg-danger">Peak</span> @endif
                                                </td>
                                                <td style="width: 35%">
                                                    {{ \Carbon\Carbon::parse($nt['start'])->format('d M, h:i A') }}</td>
                                                <td style="width: 35%">{{ \Carbon\Carbon::parse($nt['end'])->format('d M, h:i A') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info m-3">
                No Chandrashtama periods found for this year.
            </div>
        @endif
    </div>
</div>