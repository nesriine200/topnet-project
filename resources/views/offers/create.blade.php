@extends('home.index')

@section('dashboard-content')

<div class="container">
    <h1>Créer une nouvelle Offre</h1>

    <form action="{{ route('offers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nom">Nom de l'offre</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="description">Description (facultatif)</label>
            <textarea name="details" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection