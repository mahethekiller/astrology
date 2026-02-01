@if(isset($data['data']))
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="btn-group mb-3" role="group">
                @php
                    $charts = [

                        'rasi',
                        'navamsa',
                        'lagna',
                        'trimsamsa',
                        'drekkana',
                        'chaturthamsa',
                        'dasamsa',
                        'ashtamsa',
                        'dwadasamsa',
                        'shodasamsa',
                        'hora',
                        'akshavedamsa',
                        'shashtyamsa',
                        'panchamsa',
                        'khavedamsa',
                        'saptavimsamsa',
                        'shashtamsa',
                        'chaturvimsamsa',
                        'saptamsa',
                        'vimsamsa',
                        'upagraha',
                        'bhava',
                        'sun',
                        'moon'
                    ];
                    $currentChart = $request->get('chart_type', 'rasi');
                @endphp

                <a href="{{ route('kundli.detailed', array_merge($currentParams, ['tab' => 'chart', 'chart_type' => 'all'])) }}"
                    class="btn btn-sm {{ $currentChart === 'all' ? 'btn-primary' : 'btn-outline-primary' }} m-1">
                    All Charts
                </a>

                @foreach($charts as $c)
                    <a href="{{ route('kundli.detailed', array_merge($currentParams, ['tab' => 'chart', 'chart_type' => $c])) }}"
                        class="btn btn-sm {{ $currentChart === $c ? 'btn-primary' : 'btn-outline-primary' }} m-1">
                        {{ ucfirst($c) }}
                    </a>
                @endforeach
            </div>

            @if(isset($data['type']) && $data['type'] === 'multiple')
                <div class="row">
                    @foreach($data['data'] as $type => $svg)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-light fw-bold">{{ ucfirst($type) }}</div>
                                <div class="card-body p-2 text-center bg-white">
                                    <div class="chart-container d-inline-block" style="max-width: 100%; overflow: hidden;">
                                        {!! $svg !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="chart-container d-inline-block p-2 border rounded shadow-sm bg-white">
                    {!! $data['data'] !!}
                </div>
            @endif
        </div>
    </div>
@endif