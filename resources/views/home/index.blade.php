@extends('layouts.app')

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Menu -->
        @include('home.menu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- Notifications -->
                        @hasanyrole('apporteur')
                            <li class="nav-item navbar-dropdown dropdown-user dropdown mx-2">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-bell"></i>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <span class="dropdown-header">
                                        {{ auth()->user()->notifications->count() }} Notifications
                                    </span>
                                    <a href="{{ route('notifications.markAllRead') }}"
                                        class="dropdown-item dropdown-footer">
                                        Marquer tout comme lu
                                    </a>
                                    @forelse(auth()->user()->notifications as $notification)
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('notifications.show', $notification->id) }}"
                                            class="dropdown-item @if (is_null($notification->read_at)) unread-notification @else read-notification @endif">
                                            {{ $notification->data['message'] }}
                                            <span
                                                class="text-muted text-sm">{{ $notification->created_at->diffForHumans() }}
                                        </a>
                                    @empty
                                        <a href="#" class="dropdown-item">Aucune notification</a>
                                    @endforelse
                                    <div class="dropdown-divider"></div>
                                </div>
                            </li>
                        @endhasanyrole
                        <!-- User Profile -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown mx-2">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://static.vecteezy.com/system/resources/previews/002/002/403/non_2x/man-with-beard-avatar-character-isolated-icon-free-vector.jpg' }}"
                                        alt="{{ auth()->user()->name }}" class="rounded-circle" width="50"
                                        height="50" style="object-fit: cover;">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="avatar avatar-online">
                                                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://static.vecteezy.com/system/resources/previews/002/002/403/non_2x/man-with-beard-avatar-character-isolated-icon-free-vector.jpg' }}"
                                                    alt="{{ auth()->user()->name }}" class="rounded-circle"
                                                    width="50" height="50" style="object-fit: cover;">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <div class="mb-2">
                                                    <small class="text-primary">{{ Auth::user()->name }}</small>
                                                </div>
                                                <div>
                                                    <small class="text-muted">
                                                        <span class="badge bg-primary">
                                                            {{ auth()->user()->roles?->first()?->show_as }}
                                                        </span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>

                                <li>
                                    <a href="{{ url('/users/' . auth()->user()->id) }}" class="dropdown-item">
                                        <i class="bx bx-user me-2"></i>
                                        <span class="align-middle">Mon profil</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <form class="dropdown-item" id="logout-form" action="{{ route('logout') }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="dropdown-item"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- / User -->
                    </ul>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <br>

                @if (session('success'))
                    <div class="alert alert-success mx-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success mx-3">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('dashboard-content')

                <div class="content-backdrop fade"></div>
            </div>
            <!-- / Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
