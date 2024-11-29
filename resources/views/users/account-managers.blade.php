@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h1 class="mb-4">Liste des chargés de compte</h1>
        <div class="row">
            @forelse($users_charges as $users_charge)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ $users_charge->profile_image ? asset($users_charge->profile_image) : 'https://via.placeholder.com/150' }}" class="rounded-circle mb-3" alt="{{ $users_charge->name }}" width="100" height="100">
                            <h5 class="card-title">{{ $users_charge->name }}</h5>
                            <p class="card-text">{{ $users_charge->email }}</p>
                            <a href="{{ route('users.show', $users_charge->id) }}" class="btn btn-primary btn-sm">Voir le profil</a>

                            <!-- Assign Apporteurs Button -->
                            <a href="javascript:void(0);" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#assignApporteursModal" title="Assigner des apporteurs" data-user-id="{{ $users_charge->id }}" data-user-name="{{ $users_charge->name }}">
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
    <div class="modal fade" id="assignApporteursModal" tabindex="-1" aria-labelledby="assignApporteursModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignApporteursModalLabel">Sélectionner des apporteurs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignApporteursModalForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="apporteurs">Sélectionner des apporteurs</label>
                            <select id="apporteurs" name="apporteurs[]" class="form-control" multiple="multiple">
                                @foreach ($users_apporteurs as $users_apporteur)
                                    <option value="{{ $users_apporteur->id }}">{{ $users_apporteur->name }}</option>
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
        // Dynamically set the form action when the modal is shown
        $('#assignApporteursModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var userId = button.data('user-id'); // Extract user ID from data attribute

            // Update the form action dynamically
            var form = $(this).find('form');
            form.attr('action', '/user/' + userId + '/assign-apporteurs');
        });
    </script>
@endpush
