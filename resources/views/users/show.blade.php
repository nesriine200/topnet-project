@extends('home.index')

@section('dashboard-content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Profile Card -->
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-light rounded">
                    <div class="card-body text-center">
                        <!-- Profile Image -->
                        <div class="mb-4">
                            @if ($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image"
                                    class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                            @else
                                <img src="https://static.vecteezy.com/system/resources/previews/002/002/403/non_2x/man-with-beard-avatar-character-isolated-icon-free-vector.jpg"
                                    alt="Default Profile" class="rounded-circle" width="150" height="150"
                                    style="object-fit: cover;">
                            @endif
                        </div>

                        <!-- Profile Information -->
                        <h3 class="mb-3">{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>

                        <div class="mt-3">
                            <h5>Rôles:</h5>
                            <ul class="list-inline">
                                <span class="badge bg-primary">
                                    {{ $user->roles?->first()?->show_as }}
                                </span>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
