@extends('home.index')

@section('dashboard-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('opportunities.update', $opportunity->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Client Field -->
                <div class="row mb-3">
                    <label for="client">Client</label>
                    <input type="text" name="client" id="client" class="form-control @error('client') is-invalid @enderror" value="{{ old('client', $opportunity->client) }}" required>
                    @error('client')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Etat Field -->
                <div class="row mb-3">
                    <label for="etat">État</label>
                    <select name="etat" id="etat" class="form-control @error('etat') is-invalid @enderror">
                        <option value="en cours" {{ old('etat', 'en cours') == 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="valide" {{ old('etat', $opportunity->etat) == 'valide' ? 'selected' : '' }}>Valide</option>
                        <option value="non valide" {{ old('etat', $opportunity->etat) == 'non valide' ? 'selected' : '' }}>Non valide</option>
                    </select>
                    @error('etat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Matricule Client Field -->
                <div class="row mb-3">
                    <label for="matricule_client">Matricule Client</label>
                    <input type="text" name="matricule_client" id="matricule_client" class="form-control @error('matricule_client') is-invalid @enderror" value="{{ old('matricule_client', $opportunity->matricule_client) }}" required>
                    @error('matricule_client')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description Field -->
                <div class="row mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $opportunity->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <br>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection