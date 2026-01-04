@extends('layouts.app')

@section('title', 'Data Tables')
@section('page-title', 'Data Tables')

@section('content')
    <!-- Basic Table -->
    <div class="table-container mb-4">
        <div class="table-header">
            <h3>Users Table</h3>
            <button class="btn btn-primary btn-sm">Export</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                        <td>
                            <span class="badge bg-{{ $user['status'] == 'active' ? 'success' : ($user['status'] == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user['status']) }}
                            </span>
                        </td>
                        <td>{{ date("M j, Y") }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- DataTables Example -->
    <div class="table-container mb-4">
        <div class="table-header">
            <h3>DataTables Enhanced</h3>
            <button class="btn btn-success btn-sm" id="addUserBtn">Add User</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="dataTableExample">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>USR-{{ str_pad($user['id'], 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ strtolower(str_replace(' ', '', $user['name'])) }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>(555) 123-4567</td>
                        <td>{{ date("M j, Y") }}</td>
                        <td>
                            <span class="badge bg-{{ $user['status'] == 'active' ? 'success' : ($user['status'] == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user['status']) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Products Table</h3>
            <div class="btn-group">
                <button class="btn btn-outline-secondary btn-sm active">All</button>
                <button class="btn btn-outline-secondary btn-sm">Active</button>
                <button class="btn btn-outline-secondary btn-sm">Inactive</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Sales</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td>Product {{ $i }}</td>
                        <td>Category {{ $i }}</td>
                        <td>{{ rand(10, 100) }}</td>
                        <td>{{ rand(10, 100) }}</td>
                        <td>{{ rand(10, 100) }}</td>
                        <td>{{ rand(1, 5) }}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('#dataTableExample').DataTable({
            "pageLength": 5,
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries"
            }
        });
    });

    // Add user button functionality
    document.getElementById('addUserBtn')?.addEventListener('click', function() {
        alert('Add User functionality would go here!');
    });
</script>
@endpush
