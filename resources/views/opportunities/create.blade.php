{{--@extends('layouts.app')--}}
@extends('home.index')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Create Opportunity</h1>
                <form action="{{ route('opportunities.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="client">Client</label>
                        <input type="text" name="client" id="client" class="form-control" required>
                    </div>
                    <div class="form-group">
{{--                        <label for="etat">État</label>--}}
{{--                        <select name="etat" id="etat" class="form-control">--}}
{{--                            <option value="en cours" {{ old('etat', 'en cours') == 'en cours' ? 'selected' : '' }}>En cours</option>--}}
{{--                            <option value="valide" {{ old('etat') == 'valide' ? 'selected' : '' }}>Valide</option>--}}
{{--                            <option value="non valide" {{ old('etat') == 'non valide' ? 'selected' : '' }}>Non valide</option>--}}
{{--                        </select>--}}
                    </div>

                    <div class="form-group">
                        <label for="matricule_client">Matricule Client</label>
                        <input type="text" name="matricule_client" id="matricule_client" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="offer_id">Choisir une offre</label>
                        <select name="offer_id" id="offer_id" class="form-control">
                            @foreach($offers as $offer)
                                <option value="{{ $offer->id }}">{{ $offer->name }} - {{ $offer->price }} €</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
