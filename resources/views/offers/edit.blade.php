@extends('home.index')

@section('dashboard-content')
<div class="container">
    <h1>Modifier l'Offre</h1>

    <form action="{{ route('offers.update', $offer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nom">Nom de l'offre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $offer->name) }}" required>
        </div>

        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $offer->price) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description </label>
            <textarea name="details" class="form-control">{{ old('details', $offer->details) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour l'offre</button>
    </form>

    <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">Retour à la liste des offres</a>
</div>
@endsection