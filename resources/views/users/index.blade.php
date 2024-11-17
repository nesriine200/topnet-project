@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h1>Liste des utilisateurs</h1>



        <!-- Search Form -->
        <form action="{{ route('users.index') }}" method="GET" id="search-form">
            <div class="input-group mb-3">
                <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher un utilisateur">
            </div>
        </form>

        <!-- Users Table -->
        <div class="card p-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôles</th> <!-- Colonne pour afficher les rôles -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="user-list">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ $user->roles?->first()?->show_as }}
                            </td>
                            <td>
                                <!-- Actions -->
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm"
                                    title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#assignApporteursModal" title="Assigner des apporteurs"
                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                    <i class="fas fa-user-plus"></i>
                                </a>


                                <!-- Delete Form -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"
                                        title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="assignApporteursModal" tabindex="-1" aria-labelledby="assignApporteursModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignApporteursModalLabel">Sélectionner des apporteurs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ url('/user/' . $user->id . '/assign-apporteurs') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="apporteurs">Sélectionner des apporteurs</label>
                                <select id="apporteurs" name="apporteurs[]" class="form-control" multiple="multiple">
                                    @foreach ($chargeUsers as $chargeUser)
                                        <option value="{{ $chargeUser->id }}" @selected(in_array($chargeUser->id, $user->apporteurs->pluck('id')->toArray()))>
                                            {{ $chargeUser->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Assigner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Search Script -->
    <script type="text/javascript">
        $('#apporteurs').select2({
            placeholder: "Sélectionner des apporteurs",
            allowClear: true
        });

        $('#search').on('keyup', function() {
            let searchQuery = $(this).val();

            // AJAX Request for Searching Users
            $.ajax({
                url: "{{ url('/users') }}",
                type: "GET",
                data: {
                    search: searchQuery
                },
                success: function(data) {
                    // Clear existing table rows
                    $('#user-list').empty();

                    // Populate table with search results
                    data.forEach(function(user) {
                        // Handle displaying roles
                        let roles = '';
                        user.roles.forEach(function(role) {
                            roles += `<label>${role}</label> `;
                        });

                        // Append new rows to table
                        $('#user-list').append(
                            `<tr>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${roles}</td>
                                        <td>
                                            <a href="/users/${user.id}" class="btn btn-info">Voir</a>
                                            <a href="/users/${user.id}/edit" class="btn btn-warning">Modifier</a>
                                            <form action="/users/${user.id}" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>`
                        );
                    });
                },
                error: function() {
                    console.log('Erreur lors de la recherche');
                }
            });
        });
    </script>
@endpush
