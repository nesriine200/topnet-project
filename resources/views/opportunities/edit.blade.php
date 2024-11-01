{{--@extends('layouts.app')--}}

{{--@section('content')--}}
@extends('home.index')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Edit Opportunity</h1>
                <form action="{{ route('opportunities.update', $opportunity->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="client">Client</label>
                        <input type="text" name="client" id="client" class="form-control" value="{{ $opportunity->client }}" required>
                    </div>
                    <div class="form-group">
                        <label for="etat">État</label>
                        <select name="etat" id="etat" class="form-control">
                            <option value="en cours" {{ old('etat', 'en cours') == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="valide" {{ old('etat', $opportunity->etat) == 'valide' ? 'selected' : '' }}>Valide</option>
                            <option value="non valide" {{ old('etat', $opportunity->etat) == 'non valide' ? 'selected' : '' }}>Non valide</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="matricule_client">Matricule Client</label>
                        <input type="text" name="matricule_client" id="matricule_client" class="form-control" value="{{ $opportunity->matricule_client }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" required>{{ $opportunity->description }}</textarea>
                    </div>

{{--                    <div class="form-group">--}}
{{--                        <label for="commissions">Commissions</label>--}}
{{--                        <input type="text" name="commissions" class="form-control" id="commissions" value="{{ old('commissions', $opportunity->commissions) }}">--}}
{{--                    </div>--}}
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
