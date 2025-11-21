@extends('admin.layouts.app')

@section('title', 'Sliders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Sliders</h3>
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Add New Slider</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Group</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sliders as $slider)
                            <tr>
                                <td>{{ $slider->id }}</td>
                                <td>
                                    <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" width="80" height="50" style="object-fit: cover;">
                                </td>
                                <td>{{ $slider->title ?? 'No Title' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $slider->group }}</span>
                                </td>
                                <td>{{ $slider->order }}</td>
                                <td>
                                    <form action="{{ route('admin.sliders.toggle-status', $slider) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $slider->is_active ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
                <div class="d-flex p-2 ">
                        {{ $sliders->links() }}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
