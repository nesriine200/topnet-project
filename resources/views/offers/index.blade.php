@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h1>Liste des Offres</h1>
        @hasanyrole('charge|admin')
            <a href="{{ route('offers.create') }}" class="btn btn-primary mb-3">Créer une nouvelle offre</a>
        @endhasanyrole
        <!-- Formulaire de recherche -->
        <form action="{{ route('offers.index') }}" method="GET" id="search-form" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher une offre">
            </div>
        </form>

        <!-- Conteneur pour les résultats -->
        <div class="card p-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="offer-list">
                    @foreach ($offers as $offer)
                        <tr>
                            <td>{{ $offer->name }}</td>
                            <td>{{ $offer->price }} €</td>
                            <td>{{ $offer->details }}</td>
                            <td>
                                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-info">Voir</a>
                                @hasanyrole('charge|admin')
                                    <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-warning">Modifier</a>
                                    <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                                            Supprimer
                                        </button>
                                    </form>
                                @endhasanyrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Script AJAX pour la recherche en temps réel -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();

                // Effectuer une requête AJAX chaque fois que l'utilisateur tape
                $.ajax({
                    url: "{{ route('offers.index') }}",
                    type: "GET",
                    data: {
                        search: searchQuery
                    },
                    success: function(data) {
                        // Vider le corps du tableau existant
                        $('#offer-list').empty();

                        // Ajouter les résultats au tableau
                        data.forEach(function(offer) {
                            $('#offer-list').append(
                                `<tr>
                                    <td>${offer.name}</td>
                                    <td>${offer.price} €</td>
                                    <td>${offer.details}</td>
                                    <td>
                                        <a href="/offers/${offer.id}" class="btn btn-info">Voir</a>
                                        <a href="/offers/${offer.id}/edit" class="btn btn-warning">Modifier</a>
                                        <form action="/offers/${offer.id}" method="POST" style="display:inline-block;">
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
@endpush
