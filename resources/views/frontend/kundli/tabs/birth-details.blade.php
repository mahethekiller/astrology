@if(isset($data['data']))
    <div class="row">
        <div class="col-12">
            <h5 class="text-secondary mb-3">Birth Details</h5>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width: 200px;">Date Only</th>
                        <td>{{ \Carbon\Carbon::parse($request->date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{{ \Carbon\Carbon::parse($request->time)->format('h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Place</th>
                        <td>Let: {{ $request->latitude }}, Lon: {{ $request->longitude }}</td>
                    </tr>
                    <tr>
                        <th>Timezone</th>
                        <td>{{ $data['data']['timezone'] ?? $request->timezone }}</td>
                    </tr>
                    <tr>
                        <th>Ayanamsa</th>
                        <td>{{ $data['data']['ayanamsa_name'] ?? $request->ayanamsa }} ({{ $data['data']['ayanamsa'] ?? '' }})</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if(isset($data['data']['nakshatra_details']))
            <div class="col-12 mt-4">
                <h5 class="text-secondary mb-3">Nakshatra Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">Nakshatra</th>
                            <td>{{ $data['data']['nakshatra_details']['nakshatra']['name'] }}</td>
                        </tr>
                        <tr>
                            <th>Chandra Rasi</th>
                            <td>{{ $data['data']['nakshatra_details']['chandra_rasi']['name'] }} (lord:
                                {{ $data['data']['nakshatra_details']['chandra_rasi']['lord']['name'] }})
                            </td>
                        </tr>
                        <tr>
                            <th>Soorya Rasi</th>
                            <td>{{ $data['data']['nakshatra_details']['soorya_rasi']['name'] }} (lord:
                                {{ $data['data']['nakshatra_details']['soorya_rasi']['lord']['name'] }})
                            </td>
                        </tr>
                        <tr>
                            <th>Zodiac</th>
                            <td>{{ $data['data']['nakshatra_details']['zodiac']['name'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif