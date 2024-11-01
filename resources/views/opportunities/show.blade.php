
@extends('home.index')

@section('content')
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Opportunity Details</h1>
                <style>
                    .button-group {
                        position: absolute;

                        top: 15px;
                        right: 15px;
                        display: flex;
                        gap: 10px; /* Espace de 10px entre les boutons */
                    }

                    .button-group form {
                        margin-left: 10px;
                    }

                    .btn-custom {
                        font-size: 1.2rem;
                        padding: 10px 20px;
                    }
                </style>

                <div class="container">
                    <h1>Détails de l'Opportunité</h1>

                    <div class="card position-relative">
                        <div class="card-header">
                            Opportunité #{{ $opportunity->id }}
                            <!-- Boutons de validation/invalidation -->
                            <div class="button-group">
                                <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="action" value="valide" class="btn btn-success btn-lg btn-custom">
                                        <i class="fas fa-check"></i> Valider
                                    </button>
                                </form>

                                <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="action" value="non valide" class="btn btn-danger btn-lg btn-custom">
                                        <i class="fas fa-times"></i> Invalider
                                    </button>
                                </form>
                            </div>
                        </div>
{{--    <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST" style="display: inline;">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}
{{--        <button type="submit" name="action" value="valide" class="btn btn-success">Valider</button>--}}

{{--    </form>--}}

{{--    <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST" style="display: inline;">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}
{{--        <button type="submit" name="action" value="non valide" class="btn btn-danger">Invalider</button>--}}
{{--    </form>--}}

                <div class="card">
                    <div class="card-header">
                        {{ $opportunity->client }}
                    </div>
                    <div class="card-body">
                        <p><strong>État:</strong> {{ $opportunity->etat }}</p>
                        <p><strong>Matricule Client:</strong> {{ $opportunity->matricule_client }}</p>
                        <p><strong>Description:</strong> {{ $opportunity->description }}</p>
                        <p><strong>User:</strong> {{ $opportunity->user->name }}</p>
                        <div class="form-group">
                            <strong>Commissions:</strong>
                            {{ $opportunity->commissions }}
                        </div>

                    </div>
                </div>
                <a href="{{ route('opportunities.index') }}" class="btn btn-primary mt-3">Back to Opportunities</a>
            </div>
        </div>
    </div>
@endsection
