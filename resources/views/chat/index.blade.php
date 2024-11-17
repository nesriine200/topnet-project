@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h1 class="mb-4">Liste des Discussions</h1>

        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <!-- Profile Image -->
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://static.vecteezy.com/system/resources/previews/002/002/403/non_2x/man-with-beard-avatar-character-isolated-icon-free-vector.jpg' }}"
                                alt="{{ $user->name }}" class="rounded-circle" width="50" height="50"
                                style="object-fit: cover;">

                            <div class="ms-3 flex-grow-1">
                                <h5>{{ $user->name }}</h5>
                                <p class="mb-1">{{ $user->email }}</p>
                                <small>
                                    <span class="badge bg-primary"> {{ $user->roles?->first()?->show_as }}</span>
                                </small>
                            </div>

                            <div class="ms-auto">
                                <!-- Chat Button -->
                                <a href="{{ url('/chat/' . $user->id) }}" class="btn btn-primary btn-sm">
                                    Chat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
