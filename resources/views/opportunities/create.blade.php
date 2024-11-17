@extends('home.index')

@section('dashboard-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('opportunities.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <label for="client">Client</label>
                    <input
                        type="text"
                        name="client"
                        id="client"
                        class="form-control @error('client') is-invalid @enderror"
                        value="{{ old('client') }}"
                        required>
                    @error('client')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="matricule_client">Matricule Client</label>
                    <input
                        type="text"
                        name="matricule_client"
                        id="matricule_client"
                        class="form-control @error('matricule_client') is-invalid @enderror"
                        value="{{ old('matricule_client') }}"
                        required>
                    @error('matricule_client')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="description">Description</label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        class="form-control @error('description') is-invalid @enderror"
                        value="{{ old('description') }}"
                        required>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="row mb-3">
                    <label for="offer_id">Choisir une offre</label>
                    <select
                        name="offer_id"
                        id="offer_id"
                        class="form-control @error('offer_id') is-invalid @enderror">
                        @foreach($offers as $offer)
                        <option
                            value="{{ $offer->id }}"
                            {{ old('offer_id') == $offer->id ? 'selected' : '' }}>
                            {{ $offer->name }} - {{ $offer->price }} €
                        </option>
                        @endforeach
                    </select>
                    @error('offer_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <br>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection