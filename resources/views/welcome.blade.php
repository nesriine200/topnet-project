@extends('layouts.app')
@section('content')

<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="text-center">
        <!-- Logo et Nom de l'Entreprise -->
        <img src="https://www.topnetschool.tn/wp-content/uploads/2020/10/logo-topnet-2.png" alt="Topnet Logo" class="mb-5" style="width: 300px;">

        <h1 class="display-4 mb-3">Bienvenue chez Topnet</h1>
        <p class="lead mb-4">Gérez vos contrats simplement et efficacement avec notre plateforme.</p>

        <!-- Boutons pour Se Connecter ou S'Enregistrer -->
        <div class="d-flex justify-content-center">
            <a href="{{ url('/login') }}" class="btn btn-primary btn-lg me-3">Se connecter</a>
            <a href="{{ url('/register') }}" class="btn btn-secondary btn-lg">Créer un compte</a>
        </div>
    </div>
</div>