
@extends('layouts.app')

@section('title', $user->name . ' - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/player-show.css') }}">
@endpush

@section('content')

<main class="player-profile-page">

    {{-- PROFILA GALVENE --}}
    <section class="player-profile-header">

        <div class="player-profile-identity">

            <div class="player-profile-avatar-wrap">
                @if($user->profile_picture)
                    <img
                        src="{{ asset('storage/' . $user->profile_picture) }}"
                        alt="{{ $user->name }}"
                        class="player-profile-picture"
                    >
                @else
                    <div class="player-profile-avatar">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="player-profile-info">
                <span class="player-profile-eyebrow">
                    SPĒLĒTĀJA PROFILS
                </span>

                <h1>{{ $user->name }}</h1>

                <span class="player-profile-badge">
                    🥏 Spēlētājs
                </span>
            </div>

        </div>

        <div class="player-profile-rating">
            <span>SPĒLĒTĀJA REITINGS</span>
            <strong>{{ $user->rating ?? '—' }}</strong>
            <small>Discapp Rating</small>
        </div>

    </section>

    {{-- STATISTIKA --}}
    <section class="player-profile-stats">

        <div class="player-stat-card">
            <div class="player-stat-icon">🏆</div>
            <span>Sacensības</span>
            <strong>{{ $user->competitions->count() }}</strong>
        </div>

        <div class="player-stat-card">
            <div class="player-stat-icon">🥏</div>
            <span>Izspēlētas sacensības</span>
            <strong>{{ $playedCompetitions }}</strong>
        </div>

        <div class="player-stat-card">
            <div class="player-stat-icon">🥇</div>
            <span>Uzvaras</span>
            <strong>{{ $wins }}</strong>
        </div>

    </section>

    {{-- SACENSĪBU VĒSTURE --}}
    <section class="player-history-section">

        <div class="player-history-heading">
            <div>
                <span class="player-profile-eyebrow">
                    REZULTĀTI
                </span>

                <h2>Sacensību vēsture</h2>
                <p>Spēlētāja aizvadītās sacensības un rezultāti.</p>
            </div>

            <span class="player-history-count">
                {{ $competitionHistory->count() }} ieraksti
            </span>
        </div>

        @if($competitionHistory->count() > 0)

            <div class="player-history-scroll">
                <table class="player-history-table">

                    <thead>
                        <tr>
                            <th>Sacensības</th>
                            <th>Datums</th>
                            <th>Divīzija</th>
                            <th>Rezultāts</th>
                            <th>Vieta</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($competitionHistory as $history)
                            <tr>
                                <td>
                                    <a
                                        href="{{ route('competitions.show', $history['competition']) }}"
                                        class="player-competition-link"
                                    >
                                        {{ $history['competition']->name }}
                                    </a>
                                </td>

                                <td>
                                    {{ $history['competition']->date
                                        ? \Carbon\Carbon::parse($history['competition']->date)->format('d.m.Y')
                                        : '—' }}
                                </td>

                                <td>
                                    <span class="player-division-badge">
                                        {{ $history['division'] }}
                                    </span>
                                </td>

                                <td>
                                    <strong class="player-score">
                                        @if($history['relative_to_par'] === null)
                                            —
                                        @elseif($history['relative_to_par'] == 0)
                                            E
                                        @elseif($history['relative_to_par'] > 0)
                                            +{{ $history['relative_to_par'] }}
                                        @else
                                            {{ $history['relative_to_par'] }}
                                        @endif
                                    </strong>
                                </td>

                                <td>
                                    <span class="player-place">
                                        {{ $history['place'] }}.
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

            <div class="player-history-empty">
                <div class="player-history-empty-icon">🥏</div>

                <h3>Sacensību vēsture vēl ir tukša</h3>

                <p>
                    Šis spēlētājs vēl nav pabeidzis nevienas sacensības.
                </p>
            </div>

        @endif

    </section>

    <div class="player-profile-footer">
        <a
            href="{{ route('players.index') }}"
            class="player-profile-back"
        >
            ← Atpakaļ uz spēlētājiem
        </a>
    </div>

</main>

@endsection
