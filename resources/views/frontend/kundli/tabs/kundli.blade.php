@if(isset($data['data']))
    {{-- This view works for both Basic and Advanced Kundli endpoints --}}

    <div class="row">
        {{-- Nakshatra Details --}}
        @if(isset($data['data']['nakshatra_details']))
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">Nakshatra Details</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <th>Nakshatra</th>
                                    <td>{{ $data['data']['nakshatra_details']['nakshatra']['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Chandra Rasi</th>
                                    <td>{{ $data['data']['nakshatra_details']['chandra_rasi']['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Soorya Rasi</th>
                                    <td>{{ $data['data']['nakshatra_details']['soorya_rasi']['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Zodiac</th>
                                    <td>{{ $data['data']['nakshatra_details']['zodiac']['name'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mangal Dosha Summary --}}
        @if(isset($data['data']['mangal_dosha']))
            @php
                $mangalDosha = $data['data']['mangal_dosha'];
                $hasDosha = $mangalDosha['has_mangal_dosha'] ?? $mangalDosha['has_dosha'] ?? false;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-{{ $hasDosha ? 'danger' : 'success' }}">
                    <div class="card-header bg-{{ $hasDosha ? 'danger' : 'success' }} text-white">Mangal Dosha</div>
                    <div class="card-body">
                        @if(is_array($mangalDosha['description']))
                            @foreach($mangalDosha['description'] as $desc)
                                <p class="card-text">{{ $desc }}</p>
                            @endforeach
                        @else
                            <p class="card-text">{{ $mangalDosha['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Yoga Details Summary (if present in main kundli response) --}}
    @if(isset($data['data']['yoga_details']))
        <div class="card mb-4 shadow-sm">
            <div class="card-header">Yogas</div>
            <div class="card-body">
                <div class="row">
                    @foreach(array_slice($data['data']['yoga_details'], 0, 4) as $yoga)
                        <div class="col-md-6 mb-2">
                            <strong>{{ $yoga['name'] }}</strong>
                            <p class="small text-muted mb-0">{{ Str::limit($yoga['description'], 100) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2">
                    <a href="{{ route('kundli.detailed', array_merge(request()->except('tab'), ['tab' => 'yoga'])) }}"
                        class="btn btn-sm btn-outline-primary">View All Yogas</a>
                </div>
            </div>
        </div>
    @endif
@endif