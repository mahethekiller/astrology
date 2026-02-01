@if(isset($data['data']))
    <div class="mb-4">
        <h4 class="mb-3">Total Points: <span class="badge bg-primary">{{ $data['data']['total_points'] ?? '0' }}</span></h4>

        @if(isset($data['data']['papa_samyam']['papa_planet']))
            <div class="row">
                @foreach($data['data']['papa_samyam']['papa_planet'] as $planetData)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-dark text-white fw-bold">
                                {{ $planetData['name'] }}
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Planet</th>
                                            <th>Position</th>
                                            <th>Dosha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($planetData['planet_dosha'] as $dosha)
                                            <tr>
                                                <td>{{ $dosha['name'] }}</td>
                                                <td>{{ $dosha['position'] }}</td>
                                                <td>
                                                    @if($dosha['has_dosha'])
                                                        <span class="badge bg-danger">Yes</span>
                                                    @else
                                                        <span class="badge bg-success">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@else
    <div class="alert alert-info">No Papasamyam data available.</div>
@endif