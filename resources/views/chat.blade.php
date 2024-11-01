@extends('layouts.app') <!-- Ou le layout que vous utilisez -->
@extends('home.index')
@section('content')
    <div class="container">
{{--        <h1>Chat avec {{ $user->name }}</h1>--}}

        @livewire('chat', ['userId' => $user->id]) <!-- Passer l'ID de l'utilisateur avec qui vous discutez -->
    </div>
@endsection
