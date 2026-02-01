@php
    $relationships = $data['planet_relationship'] ?? [];
    $natural = $relationships['natural_relationship'] ?? [];
    $temporal = $relationships['temporal_relationship'] ?? [];
    $compound = $relationships['compound_relationship'] ?? [];
@endphp

<ul class="nav nav-pills mb-3" id="relationship-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="natural-tab" data-bs-toggle="pill" data-bs-target="#natural" type="button"
            role="tab" aria-controls="natural" aria-selected="true">Natural</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="temporal-tab" data-bs-toggle="pill" data-bs-target="#temporal" type="button"
            role="tab" aria-controls="temporal" aria-selected="false">Temporal</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="compound-tab" data-bs-toggle="pill" data-bs-target="#compound" type="button"
            role="tab" aria-controls="compound" aria-selected="false">Compound</button>
    </li>
</ul>

<div class="tab-content" id="relationship-tabsContent">
    {{-- Natural Relationship --}}
    <div class="tab-pane fade show active" id="natural" role="tabpanel" aria-labelledby="natural-tab">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>First Planet</th>
                        <th>Second Planet</th>
                        <th>Relationship</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($natural as $rel)
                        <tr>
                            <td>{{ $rel['first_planet']['name'] }}</td>
                            <td>{{ $rel['second_planet']['name'] }}</td>
                            <td>
                                @if($rel['relationship'] == 'Friend')
                                    <span class="badge bg-success">Friend</span>
                                @elseif($rel['relationship'] == 'Enemy')
                                    <span class="badge bg-danger">Enemy</span>
                                @elseif($rel['relationship'] == 'Neutral')
                                    <span class="badge bg-secondary">Neutral</span>
                                @else
                                    {{ $rel['relationship'] }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Temporal Relationship --}}
    <div class="tab-pane fade" id="temporal" role="tabpanel" aria-labelledby="temporal-tab">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>First Planet</th>
                        <th>Second Planet</th>
                        <th>Relationship</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temporal as $rel)
                        <tr>
                            <td>{{ $rel['first_planet']['name'] }}</td>
                            <td>{{ $rel['second_planet']['name'] }}</td>
                            <td>
                                @if($rel['relationship'] == 'Friend')
                                    <span class="badge bg-success">Friend</span>
                                @elseif($rel['relationship'] == 'Enemy')
                                    <span class="badge bg-danger">Enemy</span>
                                @elseif($rel['relationship'] == 'Neutral')
                                    <span class="badge bg-secondary">Neutral</span>
                                @else
                                    {{ $rel['relationship'] }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Compound Relationship --}}
    <div class="tab-pane fade" id="compound" role="tabpanel" aria-labelledby="compound-tab">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>First Planet</th>
                        <th>Second Planet</th>
                        <th>Relationship</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compound as $rel)
                        <tr>
                            <td>{{ $rel['first_planet']['name'] }}</td>
                            <td>{{ $rel['second_planet']['name'] }}</td>
                            <td>
                                @if(str_contains($rel['relationship'], 'Friend'))
                                    <span class="badge bg-success">{{ $rel['relationship'] }}</span>
                                @elseif(str_contains($rel['relationship'], 'Enemy'))
                                    <span class="badge bg-danger">{{ $rel['relationship'] }}</span>
                                @elseif($rel['relationship'] == 'Neutral')
                                    <span class="badge bg-secondary">Neutral</span>
                                @else
                                    {{ $rel['relationship'] }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>