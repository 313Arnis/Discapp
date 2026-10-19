
@extends('layouts.app')

@section('title', 'Admin panelis - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

<div class="admin-dashboard">

    {{-- ADMIN NAVIGĀCIJA --}}
    <nav class="admin-navigation" aria-label="Admin navigācija">
        <div class="admin-navigation-links">
            <a href="{{ route('admin') }}" class="active">
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}">
                Lietotāji
            </a>

            <a href="{{ route('courses.index') }}">
                Trases
            </a>
        </div>

        <div class="admin-navigation-user">
            <span>
                Admin: {{ auth()->user()->name }}
            </span>
        </div>
    </nav>

    {{-- GALVENE --}}
    <header class="admin-welcome">
        <div>
            <span class="admin-eyebrow">
                DISCAPP ADMINISTRĀCIJA
            </span>

            <h1>
                Sveiks, {{ auth()->user()->name }}!
            </h1>

            <p>
                Pārvaldi Discapp lietotājus, disku golfa
                trases un apskati sistēmas statistiku.
            </p>
        </div>

        <div class="admin-welcome-icon" aria-hidden="true">
            ⚙
        </div>
    </header>

    {{-- STATISTIKA --}}
    <section class="admin-section">
        <div class="admin-section-heading">
            <div>
                <span class="admin-section-label">
                    PĀRSKATS
                </span>

                <h2>Sistēmas statistika</h2>
            </div>
        </div>

        <div class="admin-stats-grid">

            <div class="admin-stat-card">
                <div class="admin-stat-top">
                    <span>Lietotāji</span>
                    <span class="admin-stat-icon" aria-hidden="true">👥</span>
                </div>

                <strong>{{ $usersCount }}</strong>

                <span class="admin-stat-caption">
                    Reģistrētie lietotāji
                </span>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-top">
                    <span>Trases</span>
                    <span class="admin-stat-icon" aria-hidden="true">📍</span>
                </div>

                <strong>{{ $coursesCount }}</strong>

                <span class="admin-stat-caption">
                    Disku golfa trases
                </span>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-top">
                    <span>Sacensības</span>
                    <span class="admin-stat-icon" aria-hidden="true">🏆</span>
                </div>

                <strong>{{ $competitionsCount }}</strong>

                <span class="admin-stat-caption">
                    Izveidotās sacensības
                </span>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-top">
                    <span>Vidējais reitings</span>
                    <span class="admin-stat-icon" aria-hidden="true">📈</span>
                </div>

                <strong>{{ $averageRating ?? '—' }}</strong>

                <span class="admin-stat-caption">
                    Spēlētāju vidējais reitings
                </span>
            </div>

        </div>
    </section>

    {{-- ĀTRĀS DARBĪBAS --}}
    <section class="admin-section">

        <div class="admin-section-heading">
            <div>
                <span class="admin-section-label">
                    PĀRVALDĪBA
                </span>

                <h2>Ātrās darbības</h2>
            </div>
        </div>

        <div class="admin-actions-grid">

            {{-- LIETOTĀJI --}}
            <article class="admin-action-card">
                <div class="admin-action-icon" aria-hidden="true">
                    👥
                </div>

                <h3>Lietotāji</h3>

                <p>
                    Skati un pārvaldi Discapp lietotājus,
                    viņu lomas un reitingus.
                </p>

                <a
                    href="{{ route('admin.users') }}"
                    class="admin-action-button"
                >
                    Pārvaldīt lietotājus
                    <span aria-hidden="true">→</span>
                </a>
            </article>

            {{-- TRASES --}}
            <article class="admin-action-card">
                <div class="admin-action-icon" aria-hidden="true">
                    📍
                </div>

                <h3>Disku golfa trases</h3>

                <p>
                    Pievieno un pārvaldi trases,
                    grozus un PAR vērtības.
                </p>

                <a
                    href="{{ route('courses.index') }}"
                    class="admin-action-button"
                >
                    Pārvaldīt trases
                    <span aria-hidden="true">→</span>
                </a>
            </article>

            {{-- SACENSĪBAS --}}
            <article class="admin-action-card">
                <div class="admin-action-icon" aria-hidden="true">
                    🏆
                </div>

                <h3>Sacensības</h3>

                <p>
                    Sacensību administrēšana būs pieejama
                    nākamajā sistēmas versijā.
                </p>

                <button
                    type="button"
                    class="admin-action-button disabled"
                    disabled
                >
                    Drīzumā
                </button>
            </article>

        </div>
    </section>

    {{-- JAUNĀKIE LIETOTĀJI --}}
    <section class="admin-section">

        <div class="admin-section-heading">
            <div>
                <span class="admin-section-label">
                    LIETOTĀJI
                </span>

                <h2>Jaunākie lietotāji</h2>
            </div>

            <a
                href="{{ route('admin.users') }}"
                class="admin-view-all"
            >
                Skatīt visus →
            </a>
        </div>

        <div class="admin-users-card">

            @forelse($recentUsers as $user)
                <div class="admin-user-row">

                    <div class="admin-user-details">
                        <div
                            class="admin-user-avatar"
                            aria-hidden="true"
                        >
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>

                        <div class="admin-user-text">
                            <strong>{{ $user->name }}</strong>

                            <span>{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="admin-user-rating">
                        <span>Reitings</span>

                        <strong>
                            {{ $user->rating ?? '—' }}
                        </strong>
                    </div>

                </div>
            @empty
                <div class="admin-empty">
                    <p>Lietotāju vēl nav.</p>
                </div>
            @endforelse

        </div>
    </section>

</div>

@endsection
