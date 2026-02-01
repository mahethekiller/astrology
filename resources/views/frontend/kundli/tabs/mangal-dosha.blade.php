@if(isset($data['data']))
    @php
        $hasDosha = $data['data']['has_mangal_dosha'] ?? $data['data']['has_dosha'] ?? false;
    @endphp
    <div class="alert {{ $hasDosha ? 'alert-danger' : 'alert-success' }} mb-4" role="alert">
        <h4 class="alert-heading">{{ $hasDosha ? 'Mangal Dosha Present' : 'No Mangal Dosha' }}</h4>
        @if(isset($data['data']['type']))
            <h5 class="text-uppercase badge bg-dark">{{ $data['data']['type'] }}</h5>
        @endif
        @if(is_array($data['data']['description']))
            @foreach($data['data']['description'] as $desc)
                <p>{{ $desc }}</p>
            @endforeach
        @else
            <p>{{ $data['data']['description'] }}</p>
        @endif
    </div>

    @if(isset($data['data']['exceptions']) && count($data['data']['exceptions']) > 0)
        <div class="card mb-4 shadow-sm border-success">
            <div class="card-header bg-success text-white">Exceptions</div>
            <ul class="list-group list-group-flush">
                @foreach($data['data']['exceptions'] as $exception)
                    <li class="list-group-item">
                        @if(is_array($exception))
                            {{ implode(', ', $exception) }}
                        @else
                            {{ $exception }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($data['data']['remedies']) && count($data['data']['remedies']) > 0)
        <div class="card mb-4 shadow-sm border-info">
            <div class="card-header bg-info text-white">Remedies</div>
            <ul class="list-group list-group-flush">
                @foreach($data['data']['remedies'] as $remedy)
                    <li class="list-group-item">
                        @if(is_array($remedy))
                            {{ implode(', ', $remedy) }}
                        @else
                            {{ $remedy }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endif