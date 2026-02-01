@if(isset($data['data']))
    @php
        $hasDosha = $data['data']['has_kaal_sarp_dosha'] ?? $data['data']['has_dosha'] ?? false;
    @endphp
    <div class="alert {{ $hasDosha ? 'alert-warning' : 'alert-success' }} mb-4" role="alert">
        <h4 class="alert-heading">
            {{ $hasDosha ? 'Kaal Sarp Dosha Present' : 'No Kaal Sarp Dosha' }}
        </h4>
        @if(is_array($data['data']['description']))
            @foreach($data['data']['description'] as $desc)
                <p>{{ $desc }}</p>
            @endforeach
        @else
            <p>{{ $data['data']['description'] }}</p>
        @endif
    </div>

    @if(isset($data['data']['dosha_type']))
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Type: {{ $data['data']['dosha_type'] }}</h5>
                <p class="card-text"><strong>One Line:</strong> {{ $data['data']['one_line'] }}</p>
            </div>
        </div>
    @endif
@endif