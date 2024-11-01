@extends('home.index')

@section('content')
    <div class="container">
        @if(Auth::check())
            <div>
                <h1>Profil de {{ Auth::user()->name }}</h1>
                <p><strong>Nom:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image">
                @endif

                <p><strong>Rôles:</strong>
                    @foreach(Auth::user()->getRoleNames() as $rolename)
                        {{ $rolename }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
            </div>
        @else
            <div>
                <p>Utilisateur non authentifié</p>
            </div>
        @endif
    </div>
@endsection
