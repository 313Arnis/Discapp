
@extends('layouts.app')

@section('title', 'Lietotāju pārvaldība - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
@endpush

@section('content')

<div class="admin-users-page">

    {{-- LAPAS GALVENE --}}
    <div class="admin-users-header">
        <div>
            <span class="admin-users-eyebrow">
                DISCAPP ADMINISTRĀCIJA
            </span>

            <h1>Lietotāju pārvaldība</h1>

            <p>
                Apskati lietotājus, maini viņu lomas
                un pārvaldi reitingus.
            </p>
        </div>

        <a href="{{ route('admin') }}" class="admin-back-btn">
            ← Atpakaļ uz paneli
        </a>
    </div>

    {{-- PAZIŅOJUMI --}}
    @if(session('success'))
        <div class="admin-users-alert success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="admin-users-alert error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- LIETOTĀJU SKAITS --}}
    <div class="admin-users-overview">
        <div class="admin-users-overview-icon" aria-hidden="true">
            👥
        </div>

        <div>
            <span>Kopējais lietotāju skaits</span>
            <strong>{{ $users->count() }}</strong>
        </div>
    </div>

    {{-- LIETOTĀJU SARAKSTS --}}
    <div class="admin-users-list">

        @forelse($users as $user)
            <article class="admin-user-card">

                {{-- LIETOTĀJA INFORMĀCIJA --}}
                <div class="admin-user-info">
                    <div class="admin-user-avatar" aria-hidden="true">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>

                    <div class="admin-user-details">
                        <div class="admin-user-name-row">
                            <h2>{{ $user->name }}</h2>

                            @if($user->role === 'admin')
                                <span class="admin-role-badge admin">
                                    Administrators
                                </span>
                            @else
                                <span class="admin-role-badge user">
                                    Lietotājs
                                </span>
                            @endif

                            @if($user->id === auth()->id())
                                <span class="admin-self-badge">
                                    Tavs profils
                                </span>
                            @endif
                        </div>

                        <p>{{ $user->email }}</p>

                        <div class="admin-user-meta">
                            <span>
                                Reitings:
                                <strong>{{ $user->rating ?? '—' }}</strong>
                            </span>

                            <span>
                                Loma:
                                <strong>{{ $user->role }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- REDIĢĒŠANAS FORMA --}}
                <div class="admin-user-management">

                    <form
                        method="POST"
                        action="{{ route('admin.users.update', $user) }}"
                        class="admin-user-edit-form"
                    >
                        @csrf
                        @method('PUT')

                        <div class="admin-user-fields">

                            <div class="admin-field">
                                <label for="role_{{ $user->id }}">
                                    Lietotāja loma
                                </label>

                                <select
                                    id="role_{{ $user->id }}"
                                    name="role"
                                    required
                                >
                                    <option
                                        value="user"
                                        @selected($user->role === 'user')
                                    >
                                        Lietotājs
                                    </option>

                                    <option
                                        value="admin"
                                        @selected($user->role === 'admin')
                                    >
                                        Administrators
                                    </option>
                                </select>
                            </div>

                            <div class="admin-field">
                                <label for="rating_{{ $user->id }}">
                                    Spēlētāja reitings
                                </label>

                                <input
                                    type="number"
                                    id="rating_{{ $user->id }}"
                                    name="rating"
                                    value="{{ $user->rating }}"
                                    min="0"
                                    max="2000"
                                    required
                                >
                            </div>

                        </div>

                        <div class="admin-user-actions">
                            <button
                                type="submit"
                                class="admin-save-btn"
                            >
                                Saglabāt izmaiņas
                            </button>
                        </div>
                    </form>

                    @if($user->id !== auth()->id())
                        <form
                            method="POST"
                            action="{{ route('admin.users.destroy', $user) }}"
                            class="admin-delete-form"
                            onsubmit="return confirm('Vai tiešām vēlies dzēst šo lietotāju?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="admin-delete-btn"
                            >
                                Dzēst lietotāju
                            </button>
                        </form>
                    @endif

                </div>

            </article>
        @empty
            <div class="admin-users-empty">
                <h3>Nav lietotāju</h3>
                <p>Sistēmā pašlaik nav neviena lietotāja.</p>
            </div>
        @endforelse

    </div>

</div>

@endsection
