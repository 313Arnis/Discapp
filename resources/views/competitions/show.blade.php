<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $competition->name }} - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>

    <h2>
        <a href="/">
            DiscGolf
        </a>
    </h2>

    <div>
        <a href="/">Sākums</a>
        <a href="{{ route('competitions.index') }}">Sacensības</a>
        <a href="{{ route('profile') }}">Mans profils</a>
    </div>

    @auth
        <div class="nav-auth">

            <span>
                Sveiks, {{ auth()->user()->name }}!
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    Iziet
                </button>
            </form>

        </div>
    @endauth

</nav>


<main>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif


    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif


    <div class="competition-card">

        {{-- ======================================
             SACENSĪBU INFORMĀCIJA
        ====================================== --}}

        <div class="competition-header">

            <div class="competition-title">

                <span class="competition-label">
                    SACENSĪBAS
                </span>

                <h1>
                    {{ $competition->name }}
                </h1>

                @php
                    $statusNames = [
                        'planned' => 'Plānotas',
                        'active' => 'Notiek',
                        'finished' => 'Pabeigtas',
                        'cancelled' => 'Atceltas',
                    ];
                @endphp

                <span class="competition-status status-{{ $competition->status }}">
                    {{ $statusNames[$competition->status] ?? 'Nezināms statuss' }}
                </span>


                @if($competition->description)

                    <p class="competition-description">
                        {{ $competition->description }}
                    </p>

                @endif

            </div>


            @if($competition->max_players)

                @php
                    $participantsCount = $competition->users->count();

                    $percent = $competition->max_players > 0
                        ? min(
                            100,
                            round(
                                ($participantsCount / $competition->max_players) * 100
                            )
                        )
                        : 0;
                @endphp

                <div class="competition-capacity">

                    <span>
                        Pieteikušies
                    </span>

                    <strong>
                        {{ $participantsCount }}

                        <small>
                            / {{ $competition->max_players }}
                        </small>
                    </strong>

                </div>

            @endif

        </div>


        {{-- ======================================
             PAMATINFORMĀCIJA
        ====================================== --}}

        <div class="meta-grid">

            <div class="meta-card">

                <span class="meta-label">
                    📅 Datums
                </span>

                <strong>
                    {{ $competition->date?->format('d.m.Y') ?? $competition->date }}
                </strong>

            </div>


            <div class="meta-card">

                <span class="meta-label">
                    📍 Trase
                </span>

                <strong>
                    {{ $competition->course?->name ?? 'Nav norādīta' }}
                </strong>

            </div>


            <div class="meta-card">

                <span class="meta-label">
                    👥 Dalībnieki
                </span>

                <strong>
                    {{ $competition->users->count() }}
                </strong>

            </div>


            @if($competition->max_players)

                <div class="meta-card">

                    <span class="meta-label">
                        🏆 Maksimums
                    </span>

                    <strong>
                        {{ $competition->max_players }}
                    </strong>

                </div>

            @endif

        </div>


        {{-- ======================================
             VIETU AIZPILDĪJUMS
        ====================================== --}}

        @if($competition->max_players)

            <div class="capacity-section">

                <div class="capacity-top">

                    <span>
                        Vietu aizpildījums
                    </span>

                    <strong>
                        {{ $percent }}%
                    </strong>

                </div>

                <div class="progress-bar-container">

                    <div
                        class="progress-bar"
                        style="width: {{ $percent }}%;"
                    ></div>

                </div>

            </div>

        @endif


        <hr class="divider">


        {{-- ======================================
             DALĪBNIEKI
        ====================================== --}}

        <div class="section-title-wrapper">

            <div>

                <span class="section-label">
                    PIETEIKTIE SPĒLĒTĀJI
                </span>

                <h2>
                    Dalībnieki
                </h2>

            </div>

            <span class="badge">
                {{ $competition->users->count() }}
            </span>

        </div>


        @if($competition->users->count() > 0)

            <div class="participants">

                @foreach(
                    $competition->users->groupBy(
                        fn($player) => $player->pivot->division ?? 'Bez divīzijas'
                    ) as $division => $players
                )

                    <div class="division-group">

                        <div class="division-header">

                            <div>

                                <span class="division-label">
                                    DIVĪZIJA
                                </span>

                                <h3>
                                    {{ $division }}
                                </h3>

                            </div>

                            <span class="division-count">

                                {{ $players->count() }}

                                @if($players->count() === 1)
                                    spēlētājs
                                @else
                                    spēlētāji
                                @endif

                            </span>

                        </div>


                        <div class="participants-grid">

                            @foreach($players as $player)

                                <div class="participant-card">

                                    <div class="participant-avatar">

                                        {{ strtoupper(
                                            substr($player->name ?? '?', 0, 1)
                                        ) }}

                                    </div>

                                    <div class="participant-info">

                                        <strong>
                                            {{ $player->name ?? 'Nezināms spēlētājs' }}
                                        </strong>

                                        <span class="participant-division-tag">
                                            {{ $player->pivot->division ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    👤
                </div>

                <h3>
                    Vēl nav dalībnieku
                </h3>

                <p>
                    Šobrīd šīm sacensībām neviens nav pieteicies.
                </p>

            </div>

        @endif


        <hr class="divider">


        {{-- ======================================
             MANS REZULTĀTS
        ====================================== --}}

        @auth

            @if($competition->users->contains('id', auth()->id()))

                @php

                    $myHoleResults = $competition->holeResults
                        ->where('user_id', auth()->id());

                    $totalScore = $myHoleResults->sum('score');

                    $holesPlayed = $myHoleResults->count();

                    $playedPar = 0;

                    foreach($myHoleResults as $holeResult) {

                        $hole = $competition->course?->courseHoles
                            ?->firstWhere(
                                'id',
                                $holeResult->course_hole_id
                            );

                        if($hole) {
                            $playedPar += $hole->par;
                        }
                    }

                    $scoreToPar = $totalScore - $playedPar;

                    $totalHoles =
                        $competition->course?->courseHoles?->count() ?? 0;

                @endphp


                <div class="card">

                    <div class="section-title-wrapper">

                        <div>

                            <span class="section-label">
                                MANS REZULTĀTS
                            </span>

                            <h2>
                                Rezultātu ievade
                            </h2>

                        </div>

                    </div>


                    <div class="my-score-summary">

                        <div class="score-summary-card">

                            <span>
                                Izspēlēti grozi
                            </span>

                            <strong>
                                {{ $holesPlayed }}
                                /
                                {{ $totalHoles }}
                            </strong>

                        </div>


                        <div class="score-summary-card">

                            <span>
                                Metieni
                            </span>

                            <strong>
                                {{ $totalScore }}
                            </strong>

                        </div>


                        <div class="score-summary-card">

                            <span>
                                Pret PAR
                            </span>

                            <strong>

                                @if($holesPlayed === 0)

                                    -

                                @elseif($scoreToPar > 0)

                                    +{{ $scoreToPar }}

                                @elseif($scoreToPar < 0)

                                    {{ $scoreToPar }}

                                @else

                                    E

                                @endif

                            </strong>

                        </div>

                    </div>


                    @if(
                        $competition->status !== 'finished' &&
                        $competition->status !== 'cancelled'
                    )

                        <div style="margin-top: 20px;">

                            <a
                                href="{{ route(
                                    'competitions.scorecard',
                                    $competition
                                ) }}"
                                class="button"
                            >
                                Ievadīt / labot rezultātu
                            </a>

                        </div>

                    @else

                        <div style="margin-top: 20px;">

                            <span class="result-not-entered">
                                Rezultātu labošana ir slēgta.
                            </span>

                        </div>

                    @endif

                </div>

            @endif

        @endauth


        <hr class="divider">


        {{-- ======================================
             PILNIE SACENSĪBU REZULTĀTI
        ====================================== --}}

        <div class="competition-results">

            <div class="results-header">

                <div>

                    <span class="section-label">
                        SACENSĪBU REZULTĀTI
                    </span>

                    <h2>
                        Rezultātu tabula
                    </h2>

                    <p>
                        {{ $players->count() }}
                        {{ $players->count() === 1 ? 'dalībnieks' : 'dalībnieki' }}
                    </p>

                </div>

            </div>


            @if($players->count() === 0)

                <div class="empty-results">

                    <p>
                        Šajās sacensībās vēl nav pieteicies neviens spēlētājs.
                    </p>

                </div>

            @else

                <div class="results-table-wrapper">

                    <table class="results-table">

                        <thead>

                            <tr>

                                <th>
                                    Vieta
                                </th>

                                <th>
                                    Spēlētājs
                                </th>

                                <th>
                                    Divīzija
                                </th>

                                <th>
                                    Grozi
                                </th>

                                <th>
                                    Metieni
                                </th>

                                <th>
                                    Pret PAR
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($players as $index => $player)

                                @php
                                    $playerUser = $player['user'] ?? null;
                                @endphp

                                <tr>

                                    {{-- VIETA --}}

                                    <td class="position">

                                        {{ $index + 1 }}.

                                    </td>


                                    {{-- SPĒLĒTĀJS --}}

                                    <td class="player">

                                        @if($playerUser)

                                            <strong>
                                                {{ $playerUser->name ?? 'Nezināms spēlētājs' }}
                                            </strong>

                                        @else

                                            <strong class="score-empty">
                                                Nezināms spēlētājs
                                            </strong>

                                        @endif

                                    </td>


                                    {{-- DIVĪZIJA --}}

                                    <td>

                                        <span class="division">
                                            {{ $player['division'] ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- GROZI --}}

                                    <td>

                                        {{ $player['played'] ?? 0 }}
                                        /
                                        {{ $competition->course?->courseHoles?->count() ?? 0 }}

                                    </td>


                                    {{-- METIENI --}}

                                    <td class="total-score">

                                        @if(($player['played'] ?? 0) > 0)

                                            {{ $player['total_score'] ?? 0 }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- PRET PAR --}}

                                    <td class="relative">

                                        @if(($player['played'] ?? 0) === 0)

                                            <span class="score-empty">
                                                -
                                            </span>

                                        @elseif(($player['relative'] ?? 0) < 0)

                                            <span class="score-under">
                                                {{ $player['relative'] }}
                                            </span>

                                        @elseif(($player['relative'] ?? 0) > 0)

                                            <span class="score-over">
                                                +{{ $player['relative'] }}
                                            </span>

                                        @else

                                            <span class="score-even">
                                                E
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        {{-- ======================================
             DARBĪBAS
        ====================================== --}}

        <div class="competition-actions">

            @auth

                @if($competition->user_id === auth()->id())

                    <div class="creator-status">
                        Tu esi šo sacensību veidotājs.
                    </div>


                @elseif($competition->users->contains('id', auth()->id()))

                    @if(
                        $competition->status !== 'finished' &&
                        $competition->status !== 'cancelled'
                    )

                        <form
                            method="POST"
                            action="{{ route(
                                'competitions.leave',
                                $competition
                            ) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="button"
                            >
                                Izstāties
                            </button>

                        </form>

                    @endif


                @else

                    @if(
                        $competition->status !== 'finished' &&
                        $competition->status !== 'cancelled'
                    )

                        <a
                            href="{{ route(
                                'competitions.join',
                                $competition
                            ) }}"
                            class="button"
                        >
                            Pievienoties sacensībām
                        </a>

                    @endif

                @endif

            @endauth


            <a
                href="{{ route('competitions.index') }}"
                class="secondary-button"
            >
                Atpakaļ
            </a>

        </div>


        {{-- ======================================
             PĀRVALDĪBA
        ====================================== --}}

        @auth

            @if($competition->user_id === auth()->id())

                <div class="competition-admin-actions">

                    <a
                        href="{{ route(
                            'competitions.edit',
                            $competition
                        ) }}"
                        class="edit-link"
                    >
                        Rediģēt sacensības
                    </a>


                    <form
                        method="POST"
                        action="{{ route(
                            'competitions.destroy',
                            $competition
                        ) }}"
                        onsubmit="
                            return confirm(
                                'Vai tiešām vēlies dzēst šīs sacensības?'
                            );
                        "
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="delete-link"
                        >
                            Dzēst sacensības
                        </button>

                    </form>

                </div>

            @endif

        @endauth

    </div>

</main>


<style>

    .competition-results {
        margin-top: 30px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
    }


    .results-header {
        padding: 20px;
        border-bottom: 1px solid #ddd;
    }


    .results-header h2 {
        margin: 4px 0 0;
        font-size: 22px;
    }


    .results-header p {
        margin: 5px 0 0;
        color: #777;
        font-size: 14px;
    }


    .results-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }


    .results-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }


    .results-table th {
        background: #f7f7f7;
        color: #666;
        font-size: 13px;
        font-weight: 600;
        text-align: left;
        padding: 13px 15px;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }


    .results-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        font-size: 15px;
    }


    .results-table tbody tr:last-child td {
        border-bottom: none;
    }


    .results-table tbody tr:hover {
        background: #fafafa;
    }


    .position {
        width: 60px;
        color: #777;
        font-weight: bold;
    }


    .player {
        min-width: 160px;
    }


    .player strong {
        color: #222;
    }


    .division {
        display: inline-block;
        padding: 4px 8px;
        background: #f1f1f1;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        color: #555;
    }


    .total-score {
        font-weight: bold;
        font-size: 17px !important;
    }


    .relative {
        font-weight: bold;
        font-size: 16px !important;
    }


    .score-under {
        color: #2e9d27;
    }


    .score-over {
        color: #c0392b;
    }


    .score-even {
        color: #555;
    }


    .score-empty {
        color: #aaa;
    }


    .empty-results {
        padding: 30px 20px;
        text-align: center;
        color: #777;
    }


    @media (max-width: 600px) {

        .results-table th,
        .results-table td {
            padding: 12px 10px;
        }

        .results-header {
            padding: 16px;
        }

    }

</style>


</body>
</html>