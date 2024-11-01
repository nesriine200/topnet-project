@extends('home.index')

@section('content')
    <div class="container">
        <h1>Liste des utilisateurs</h1>
        <form action="{{ route('users.index') }}" method="GET" id="search-form">
            <div class="input-group mb-3">
                <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher un utilisateur">
            </div>
        </form>

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
                        @foreach($user->getRoleNames() as $rolename)
                            <label>{{ $rolename }}</label>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">Voir</a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Modifier</a>

                        <!-- Lien vers le chat -->
                        <a href="{{ route('chat', $user->id) }}" class="btn btn-primary">Chat avec {{ $user->name }}</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#search').on('keyup', function() {
                    let searchQuery = $(this).val();

                    // Effectuer une requête AJAX chaque fois que l'utilisateur tape
                    $.ajax({
                        url: "{{ route('users.index') }}",
                        type: "GET",
                        data: {
                            search: searchQuery
                        },
                        success: function(data) {
                            // Vider le corps du tableau existant
                            $('#user-list').empty();

                            // Ajouter les résultats au tableau
                            data.forEach(function(user) {
                                // Gérer l'affichage des rôles
                                let roles = '';
                                user.roles.forEach(function(role) {
                                    roles += `<label>${role}</label> `;
                                });

                                $('#user-list').append(
                                    `<tr>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${roles}</td>
                                        <td>
                                            <a href="/users/${user.id}" class="btn btn-info">Voir</a>
                                            <a href="/users/${user.id}/edit" class="btn btn-warning">Modifier</a>
                                            <a href="/chat/${user.id}" class="btn btn-success">Chat avec ${user.name}</a>
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
            });
        </script>
    </div>
@endsection
