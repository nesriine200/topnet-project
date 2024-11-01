
{{--@section('content')--}}
@extends('home.index')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Opportunities</h1>
                <a href="{{ route('opportunities.create') }}" class="btn btn-primary mb-3">Create Opportunity</a>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
{{--                <form action="{{ route('opportunities.index') }}" method="GET">--}}
{{--                    <div class="input-group">--}}
{{--                        <input type="text" name="search" class="form-control" placeholder="Rechercher une opportunité" value="{{ request()->query('search') }}">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <button type="submit" class="btn btn-primary">Rechercher</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
                <form action="{{ route('opportunities.index') }}" method="GET" id="search-form">
                    <div class="input-group mb-3">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher une opportunité">
                    </div>
                </form>

                <!-- Conteneur pour le tableau des résultats -->
                <div id="opportunity-list">
                    <div class="card p-2">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
{{--                            <th>ID</th>--}}
                            <th>Client</th>
                            <th>État</th>
                            <th>Matricule Client</th>
                            <th>Description</th>
{{--                            <th>User</th>--}}
                            <th>Commissions</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($opportunities as $opportunity)
                            <tr>
{{--                                <td>{{ $opportunity->id }}</td>--}}
                                <td>{{ $opportunity->client }}</td>
                                <td>{{ $opportunity->etat }}</td>
                                <td>{{ $opportunity->matricule_client }}</td>
                                <td>{{ $opportunity->description }}</td>
{{--                                <td>{{ $opportunity->user->name }}</td>--}}
                                <td>{{ $opportunity->commissions }}</td>
                                <td>
                                    <a href="{{ route('opportunities.show', $opportunity->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('opportunities.edit', $opportunity->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('opportunities.destroy', $opportunity->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    </div>
                </div>

                <!-- Ajouter le script AJAX -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#search').on('keyup', function() {
                            let searchQuery = $(this).val();

                            // Effectuer une requête AJAX chaque fois que l'utilisateur tape
                            $.ajax({
                                url: "{{ route('opportunities.index') }}",
                                type: "GET",
                                data: {
                                    search: searchQuery
                                },
                                success: function(data) {
                                    // Vider le corps du tableau existant
                                    $('#opportunity-list tbody').empty();

                                    // Ajouter les résultats au tableau
                                    data.forEach(function(opportunity) {
                                        $('#opportunity-list tbody').append(
                                            `<tr>
                                <td>${opportunity.id}</td>
                                <td>${opportunity.client}</td>
                                <td>${opportunity.etat}</td>

                                <td>${opportunity.matricule_client}</td>
                                <td>${opportunity.description}</td>

                                <td>${opportunity.user.name}</td>
                                <td>${opportunity.commissions}</td>
                                <td>
                                    <a href="/opportunities/${opportunity.id}" class="btn btn-info btn-sm">View</a>
                                    <a href="/opportunities/${opportunity.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="/opportunities/${opportunity.id}" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
        </div>
    </div>
@endsection
