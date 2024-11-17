@extends('home.index')

@section('dashboard-content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">

                {{-- Permissions Card --}}
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Permissions</h4>
                        @can('create permission')
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add Permission</a>
                        @endcan
                    </div>
                    <div class="card-body">

                        {{-- Permissions Table --}}
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="40%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                            <a href="{{ url('permissions/' . $permission->id . '/edit') }}"
                                                class="btn btn-success">
                                                Edit
                                            </a>
                                            <a href="{{ url('permissions/' . $permission->id . '/delete') }}"
                                                class="btn btn-danger mx-2">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No permissions available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
