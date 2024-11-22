@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h1 class="mb-4">Liste des chargés de compte</h1>
        <div class="row">
            @forelse($users as $user)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ $user->profile_image ? asset($user->profile_image) : 'https://via.placeholder.com/150' }}"
                                class="rounded-circle mb-3" alt="{{ $user->name }}" width="100" height="100">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text">{{ $user->email }}</p>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-sm">Voir le profil</a>

                            <!-- Assign Apporteurs Button -->
                            <a href="javascript:void(0);" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#assignApporteursModal" title="Assigner des apporteurs"
                                data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                <i class="fas fa-user-plus"></i> Assigner des apporteurs
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Aucun chargé de compte trouvé.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal for Assigning Apporteurs -->
    <div class="modal fade" id="assignApporteursModal" tabindex="-1" aria-labelledby="assignApporteursModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignApporteursModalLabel">Sélectionner des apporteurs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ url('/user/' . $user->id . '/assign-apporteurs') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="apporteurs">Sélectionner des apporteurs</label>
                            <select id="apporteurs" name="apporteurs[]" class="form-control" multiple="multiple">
                                @foreach ($chargeUsers as $chargeUser)
                                    <option value="{{ $chargeUser->id }}">{{ $chargeUser->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Assigner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        // Initialisation du composant select2
        $('#apporteurs').select2({
            placeholder: "Sélectionner des apporteurs",
            allowClear: true
        });

        // Pour afficher le nom de l'utilisateur dans le modal
        $('#assignApporteursModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userName = button.data('user-name');
            var userId = button.data('user-id');

            // Modifier l'action du formulaire pour inclure l'ID utilisateur
            var form = $(this).find('form');
            form.attr('action', '/user/' + userId + '/assign-apporteurs');
        });
    </script>
@endpush
