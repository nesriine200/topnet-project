@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <h3>Affecter un rôle à {{ $user->name }}</h3>
        <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="role">Choisissez un rôle</label>
                <select name="role" id="role" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Assigner</button>
        </form>
    </div>
@endsection
