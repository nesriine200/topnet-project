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
@endsection
