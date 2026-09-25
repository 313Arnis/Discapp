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
                    ) as $division => $divisionPlayers
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

                                {{ $divisionPlayers->count() }}

                                @if($divisionPlayers->count() === 1)
                                    spēlētājs
                                @else
                                    spēlētāji
                                @endif

                            </span>

                        </div>


                        <div class="participants-grid">

                            @foreach($divisionPlayers as $player)

                                <div class="participant-card">

                                    <div class="participant-avatar">
                                        {{ strtoupper(substr($player->name ?? '?', 0, 1)) }}
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
                                {{ $holesPlayed }}/{{ $totalHoles }}
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

                @php

                    $courseHoles = $competition->course?->courseHoles
                        ?->sortBy('hole_number')
                        ?->values() ?? collect();

                    $coursePar = $courseHoles->sum('par');

                    $previousRelative = null;
                    $previousPosition = 0;

                @endphp


                <div class="results-table-wrapper">

                    <table class="metrix-table">

                        {{-- GALVENE --}}

                        <thead>

                            <tr>

                                <th class="col-position">
                                    No
                                </th>

                                <th class="col-name">
                                    Spēlētājs
                                </th>

                                <th class="col-relative">
                                    +/-
                                </th>

                                @foreach($courseHoles as $hole)

                                    <th class="hole-heading">
                                        {{ $hole->hole_number }}
                                    </th>

                                @endforeach

                                <th class="col-relative">
                                    +/-
                                </th>

                                <th class="col-sum">
                                    Sum
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            {{-- PAR RINDA --}}

                            <tr class="par-row">

                                <td></td>

                                <td class="par-title">
                                    PAR
                                </td>

                                <td></td>

                                @foreach($courseHoles as $hole)

                                    <td class="par-cell">
                                        {{ $hole->par }}
                                    </td>

                                @endforeach

                                <td></td>

                                <td class="par-total">
                                    {{ $coursePar }}
                                </td>

                            </tr>


                            {{-- SPĒLĒTĀJI --}}

                            @foreach($players as $index => $player)

                                @php

                                    $playerUser = $player['user'] ?? null;

                                    $playerResults = $playerUser
                                        ? $competition->holeResults
                                            ->where('user_id', $playerUser->id)
                                        : collect();

                                    $played = $playerResults->count();

                                    $playerTotal = $playerResults->sum('score');

                                    $playerPlayedPar = 0;

                                    foreach($playerResults as $result) {

                                        $resultHole = $courseHoles
                                            ->firstWhere(
                                                'id',
                                                $result->course_hole_id
                                            );

                                        if($resultHole) {
                                            $playerPlayedPar += $resultHole->par;
                                        }
                                    }

                                    $playerRelative =
                                        $playerTotal - $playerPlayedPar;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | VIETA AR NEIZŠĶIRTIEM REZULTĀTIEM
                                    |--------------------------------------------------------------------------
                                    */

                                    if($played === 0) {

                                        $displayPosition = '-';

                                    } else {

                                        if(
                                            $previousRelative !== null &&
                                            $previousRelative === $playerRelative
                                        ) {

                                            $displayPosition = $previousPosition;

                                        } else {

                                            $displayPosition = $index + 1;
                                            $previousPosition = $displayPosition;

                                        }

                                        $previousRelative = $playerRelative;
                                    }

                                @endphp


                                <tr>

                                    {{-- VIETA --}}

                                    <td class="position-cell">
                                        {{ $displayPosition }}
                                    </td>


                                    {{-- SPĒLĒTĀJS --}}

                                    <td class="player-name-cell">

                                        <strong>
                                            {{ $playerUser?->name ?? 'Nezināms spēlētājs' }}
                                        </strong>

                                        @if(($player['division'] ?? null))

                                            <span class="player-division">
                                                {{ $player['division'] }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- +/- KREISAJĀ PUSĒ --}}

                                    <td class="relative-cell">

                                        @if($played === 0)

                                            -

                                        @elseif($playerRelative > 0)

                                            +{{ $playerRelative }}

                                        @elseif($playerRelative < 0)

                                            {{ $playerRelative }}

                                        @else

                                            E

                                        @endif

                                    </td>


                                    {{-- KATRS GROZS --}}

                                    @foreach($courseHoles as $hole)

                                        @php

                                            $holeResult = $playerResults
                                                ->firstWhere(
                                                    'course_hole_id',
                                                    $hole->id
                                                );

                                            $holeScore =
                                                $holeResult?->score;

                                            $difference =
                                                $holeScore !== null
                                                    ? $holeScore - $hole->par
                                                    : null;


                                            $scoreClass = '';

                                            if($difference !== null) {

                                                if($difference <= -2) {

                                                    $scoreClass =
                                                        'hole-double-under';

                                                } elseif($difference === -1) {

                                                    $scoreClass =
                                                        'hole-under';

                                                } elseif($difference === 0) {

                                                    $scoreClass =
                                                        'hole-par';

                                                } elseif($difference === 1) {

                                                    $scoreClass =
                                                        'hole-over';

                                                } else {

                                                    $scoreClass =
                                                        'hole-double-over';
                                                }
                                            }

                                        @endphp


                                        <td class="hole-score {{ $scoreClass }}">

                                            @if($holeScore !== null)

                                                {{ $holeScore }}

                                            @else

                                                <span class="no-score">
                                                    -
                                                </span>

                                            @endif

                                        </td>

                                    @endforeach


                                    {{-- +/- LABAJĀ PUSĒ --}}

                                    <td class="relative-cell">

                                        @if($played === 0)

                                            -

                                        @elseif($playerRelative > 0)

                                            +{{ $playerRelative }}

                                        @elseif($playerRelative < 0)

                                            {{ $playerRelative }}

                                        @else

                                            E

                                        @endif

                                    </td>


                                    {{-- SUMMA --}}

                                    <td class="sum-cell">

                                        @if($played > 0)

                                            {{ $playerTotal }}

                                        @else

                                            -

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

    /*
    |--------------------------------------------------------------------------
    | REZULTĀTU BLOKS
    |--------------------------------------------------------------------------
    */

    .competition-results {
        margin-top: 30px;
        background: #fff;
        border: 1px solid #dcdcdc;
        border-radius: 8px;
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


    /*
    |--------------------------------------------------------------------------
    | HORIZONTĀLA SCROLLOŠANA
    |--------------------------------------------------------------------------
    */

    .results-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }


    /*
    |--------------------------------------------------------------------------
    | METRIX STILA TABULA
    |--------------------------------------------------------------------------
    */

    .metrix-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1150px;
        background: white;
    }


    .metrix-table th,
    .metrix-table td {
        border-right: 1px solid #d9e0e0;
        border-bottom: 1px solid #d9e0e0;
        text-align: center;
        padding: 0;
        height: 42px;
        font-size: 14px;
        white-space: nowrap;
    }


    .metrix-table th:last-child,
    .metrix-table td:last-child {
        border-right: none;
    }


    /*
    |--------------------------------------------------------------------------
    | GALVENE
    |--------------------------------------------------------------------------
    */

    .metrix-table thead th {
        background: #fafafa;
        color: #111;
        font-weight: 700;
        padding: 10px 8px;
    }


    .col-position {
        width: 55px;
        min-width: 55px;
    }


    .col-name {
        min-width: 210px;
        text-align: left !important;
        padding-left: 12px !important;
    }


    .col-relative {
        min-width: 55px;
    }


    .col-sum {
        min-width: 65px;
    }


    .hole-heading {
        width: 45px;
        min-width: 45px;
    }


    /*
    |--------------------------------------------------------------------------
    | PAR RINDA
    |--------------------------------------------------------------------------
    */

    .par-row td {
        background: #f8faf9;
        font-weight: 500;
        height: 40px;
    }


    .par-title {
        text-align: right !important;
        padding-right: 15px !important;
        font-weight: 700 !important;
    }


    .par-cell {
        font-weight: 600 !important;
    }


    .par-total {
        font-weight: 700 !important;
    }


    /*
    |--------------------------------------------------------------------------
    | VIETA
    |--------------------------------------------------------------------------
    */

    .position-cell {
        font-weight: 700;
        color: #222;
    }


    /*
    |--------------------------------------------------------------------------
    | SPĒLĒTĀJS
    |--------------------------------------------------------------------------
    */

    .player-name-cell {
        text-align: left !important;
        min-width: 210px;
        padding: 7px 12px !important;
    }


    .player-name-cell strong {
        display: block;
        color: #111;
        font-size: 14px;
    }


    .player-division {
        display: block;
        margin-top: 2px;
        color: #888;
        font-size: 10px;
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | +/- UN SUMMA
    |--------------------------------------------------------------------------
    */

    .relative-cell {
        min-width: 55px;
        font-weight: 700;
    }


    .sum-cell {
        min-width: 65px;
        font-weight: 700;
        font-size: 15px !important;
    }


    /*
    |--------------------------------------------------------------------------
    | GROZU REZULTĀTI
    |--------------------------------------------------------------------------
    */

    .hole-score {
        width: 45px;
        min-width: 45px;
        font-weight: 600;
        position: relative;
    }


    /*
    | Eagle vai labāk
    */

    .hole-double-under {
        background: #83d96b;
        color: #111;
    }


    /*
    | Birdie
    */

    .hole-under {
        background: #b7eaa5;
        color: #111;
    }


    /*
    | Par
    */

    .hole-par {
        background: #ffffff;
        color: #111;
    }


    /*
    | Bogey
    */

    .hole-over {
        background: #f8ddd4;
        color: #111;
    }


    /*
    | Double bogey vai sliktāk
    */

    .hole-double-over {
        background: #f3ae9c;
        color: #111;
    }


    /*
    | Sarkana līnija virs rezultāta, ja virs PAR
    */

    .hole-over::before,
    .hole-double-over::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #f04424;
    }


    .no-score {
        color: #bbb;
        font-weight: 400;
    }


    /*
    |--------------------------------------------------------------------------
    | HOVER
    |--------------------------------------------------------------------------
    */

    .metrix-table tbody tr:not(.par-row):hover td {
        filter: brightness(0.97);
    }


    /*
    |--------------------------------------------------------------------------
    | TUKŠI REZULTĀTI
    |--------------------------------------------------------------------------
    */

    .empty-results {
        padding: 30px 20px;
        text-align: center;
        color: #777;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILĀ VERSIJA
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {

        .results-header {
            padding: 16px;
        }


        .metrix-table th,
        .metrix-table td {
            font-size: 12px;
        }


        .col-name,
        .player-name-cell {
            min-width: 160px;
        }


        .hole-heading,
        .hole-score {
            width: 38px;
            min-width: 38px;
        }

    }

</style>


</body>
</html>