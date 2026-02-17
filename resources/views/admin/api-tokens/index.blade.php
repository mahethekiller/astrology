@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">API Tokens</h6>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('new_token'))
                            <div class="alert alert-warning border-left-warning shadow h-100 py-2 mb-4" role="alert">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                New API Token Generated
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Make sure to copy your new personal access token now. You won't be able to see
                                                it again!
                                            </div>
                                            <div class="mt-3 p-3 bg-light border border-dashed rounded">
                                                <code id="newToken" class="h6">{{ session('new_token') }}</code>
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard()">
                                                    <i class="bi bi-clipboard"></i> Copy
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="bi bi-shield-lock h2 text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function copyToClipboard() {
                                    var text = document.getElementById('newToken').innerText;
                                    navigator.clipboard.writeText(text).then(function () {
                                        alert('Token copied to clipboard!');
                                    });
                                }
                            </script>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Generate New Token</h5>
                                        <form action="{{ route('admin.api-tokens.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="token_name" class="form-label">Token Name (e.g., Development,
                                                    Mobile App)</label>
                                                <input type="text" class="form-control" id="token_name" name="token_name"
                                                    required placeholder="Enter token name">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Create Token</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Active Tokens</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Last Used</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tokens as $token)
                                        <tr>
                                            <td>{{ $token->name }}</td>
                                            <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                                            </td>
                                            <td>{{ $token->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('admin.api-tokens.destroy', $token->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to revoke this token?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Revoke
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No active tokens found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection