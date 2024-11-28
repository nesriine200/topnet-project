@extends('home.index')

@section('dashboard-content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="container">
                <h1>Détails de l'Opportunité</h1>

                <div class="card position-relative">
                    {{-- <div class="card-header">
                        Opportunité #{{ $opportunity->id }}
                    </div> --}}

                    <div class="card-body">
                        <!-- Boutons de validation/invalidation -->
                        <div class="button-group mb-3">
                            @hasanyrole('charge|admin')
                                <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="action" value="valide"
                                        class="btn btn-success btn-lg btn-custom">
                                        <i class="fas fa-check"></i> Valider
                                    </button>
                                </form>
                            @endhasanyrole
                            @hasanyrole('charge|admin')
                                <form action="{{ route('opportunities.validate', $opportunity->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="action" value="non valide"
                                        class="btn btn-danger btn-lg btn-custom">
                                        <i class="fas fa-times"></i> Invalider
                                    </button>
                                </form>
                            @endhasanyrole
                        </div>

                        <!-- Détails de l'opportunité -->
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
                                    <strong>Commissions:</strong> {{ $opportunity->commissions }}
                                </div>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <a href="{{ route('opportunities.index') }}" class="btn btn-primary mt-3">Back to Opportunities</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
