@extends('home.index')

@section('dashboard-content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Créer un Rôle</a>

                {{-- Roles Card --}}
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Roles</h4>
                        @can('create role')
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
                        @endcan
                    </div>
                    <div class="card-body">

                        {{-- Roles Table --}}
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="40%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $role->show_as }}</td>
                                        <td>
                                            <a href="{{ url('roles/' . $role->id . '/give-permissions') }}"
                                                class="btn btn-success btn-sm">
                                                Affecter les permissions
                                            </a>
                                            <a
                                                href="{{ url('roles/' . $role->id . '/edit') }}"class="btn btn-secondary btn-sm">
                                                Modifier
                                            </a>
                                            <a href="{{ url('roles/' . $role->id . '/delete') }}"
                                                class="btn btn-danger btn-sm">
                                                Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No roles available.</td>
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
