@extends('home.index')

@section('content')
    <div class="container">
        <h1>Détails de l'Offre</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $offer->name }}</h5>
                <p class="card-text"><strong>Prix :</strong> {{ $offer->price}} €</p>
                <p class="card-text"><strong>Description :</strong> {{ $offer->details }}</p>
{{--                <p class="card-text"><strong>Date de création :</strong> {{ $offer->created_at->format('d/m/Y') }}</p>--}}
{{--                <p class="card-text"><strong>Dernière modification :</strong> {{ $offer->updated_at->format('d/m/Y') }}</p>--}}
            </div>
        </div>

        <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">Retour à la liste des offres</a>
        <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-warning mt-3">Modifier l'offre</a>

        <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">Supprimer l'offre</button>
        </form>
    </div>
@endsection
