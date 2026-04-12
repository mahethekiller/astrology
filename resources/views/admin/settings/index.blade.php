@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Global Settings</h4>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Commission Settings</h4>

                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="global_chat_commission" class="form-label">Global Chat Commission (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="global_chat_commission"
                                        name="global_chat_commission" value="{{ $chatCommission }}" min="0" max="100"
                                        step="0.01" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Percentage of earning deducted from astrologer for each chat.</div>
                            </div>

                            <div class="mb-3">
                                <label for="global_voice_commission" class="form-label">Global Voice Call Commission
                                    (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="global_voice_commission"
                                        name="global_voice_commission" value="{{ $callCommission }}" min="0" max="100"
                                        step="0.01" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Percentage of earning deducted from astrologer for each voice call.
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-md">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Homepage Statistics (Section 6)</h4>

                        <form action="{{ route('admin.settings.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="global_chat_commission" value="{{ $chatCommission }}">
                            <input type="hidden" name="global_voice_commission" value="{{ $callCommission }}">

                            <div class="mb-3">
                                <label for="stat_total_astrologers" class="form-label">Total Astrologers (e.g. 50000+)</label>
                                <input type="text" class="form-control" id="stat_total_astrologers"
                                    name="stat_total_astrologers" value="{{ $statTotalAstrologers }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="stat_years_excellence" class="form-label">Years of Excellence (e.g. 11+)</label>
                                <input type="text" class="form-control" id="stat_years_excellence"
                                    name="stat_years_excellence" value="{{ $statYearsExcellence }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="stat_happy_customers" class="form-label">Happy Customers (e.g. 500+)</label>
                                <input type="text" class="form-control" id="stat_happy_customers"
                                    name="stat_happy_customers" value="{{ $statHappyCustomers }}" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary w-md">Save Statistics</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection