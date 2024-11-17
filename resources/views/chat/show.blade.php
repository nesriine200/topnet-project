@extends('home.index')
@section('dashboard-content')
    <div class="container">
        @livewire('chat', ['userId' => $user->id])
    </div>
@endsection
