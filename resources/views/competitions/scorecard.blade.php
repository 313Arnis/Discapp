<!DOCTYPE html>

<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>{{ $competition->name }} - Rezultāts</title>

<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<style>
    body {
        background: #f7f7f4;
    }

    .scorecard-page {
        max-width: 730px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .scorecard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .scorecard-header h1 {
        margin: 0;
        font-size: 30px;
    }

    .result-button {
        background: #ff6500;
        color: white;
        border: none;
        padding: 11px 24px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
    }

    .hole-indicators {
        display: flex;
        justify-content: center;
        gap: 7px;
        margin: 20px 0 10px;
        flex-wrap: wrap;
    }

    .hole-indicator {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #d9dedd;
        display: block;
        text-decoration: none;
    }

    .hole-indicator.active {
        background: #35b000;
        outline: 2px solid #35b000;
        outline-offset: 1px;
    }

    .hole-indicator.completed {
        background: #777;
    }

    .hole-info {
        text-align: center;
        margin-bottom: 22px;
    }

    .hole-info-title {
        font-size: 20px;
        font-weight: bold;
        color: #222;
    }

    .hole-info-subtitle {
        margin-top: 5px;
        color: #555;
        font-size: 15px;
    }

    .score-card {
        border-top: 1px solid #ddd;
        background: transparent;
    }

    .player-row {
        min-height: 70px;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .player-position {
        width: 30px;
        text-align: right;
        font-size: 18px;
        color: #777;
    }

    .player-name {
        flex: 1;
        font-size: 19px;
        font-weight: 500;
    }

    .penalty {
        color: #bbb;
        font-size: 14px;
        font-weight: bold;
    }

    .score-input {
        width: 60px;
        height: 60px;
        border: 2px solid #ff6500;
        border-radius: 4px;
        background: #fffdf9;
        font-size: 25px;
        text-align: center;
        outline: none;
    }

    .score-input:focus {
        border-color: #222;
    }

    .relative-score {
        width: 35px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .under-par {
        color: #2e9d27;
    }

    .over-par {
        color: #c0392b;
    }

    .even-par {
        color: #555;
    }

    .score-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 22px;
    }

    .nav-hole {
        min-width: 100px;
        padding: 11px 18px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #222;
        text-align: center;
        font-weight: bold;
    }

    .nav-hole:hover {
        background: #eee;
    }

    .save-next {
        min-width: 140px;
        background: #ff6500;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }

    .save-next:hover {
        background: #e95700;
    }

    .summary {
        margin-top: 30px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 18px;
    }

    .summary-title {
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        color: #555;
    }

    .summary-row strong {
        color: #222;
    }

    .success-message {
        background: #e9f7e8;
        color: #287a25;
        padding: 11px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
    }

    .error-message {
        background: #ffe5e5;
        color: #b00020;
        padding: 11px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
    }

    .par-value {
        font-weight: bold;
    }

    @media (max-width: 600px) {
        .scorecard-page {
            margin-top: 15px;
            padding: 0 10px;
        }

        .scorecard-header h1 {
            font-size: 23px;
        }

        .player-row {
            gap: 8px;
        }

        .player-name {
            font-size: 15px;
        }

        .score-input {
            width: 52px;
            height: 52px;
        }

        .penalty {
            display: none;
        }

        .relative-score {
            width: 25px;
        }
    }
</style>


</head>

<body>

<nav>
    <h2>
        <a href="/">
            Discapp
        </a>
    </h2>


<div>
    <a href="{{ route('competitions.index') }}">
        Sacensības
    </a>

    <a href="{{ route('profile') }}">
        Profils
    </a>
</div>


</nav>

<main class="scorecard-page">


{{-- HEADER --}}

<div class="scorecard-header">

    <h1>
        {{ $competition->name }}
    </h1>

    <div class="result-button">
        Rezultāts
    </div>

</div>


{{-- GROZU INDIKATORI --}}

<div class="hole-indicators">

    @foreach ($courseHoles as $hole)

        <a
            href="{{ route(
                'competitions.scorecard',
                [
                    'competition' => $competition,
                    'hole' => $hole->hole_number
                ]
            ) }}"
            class="hole-indicator
                @if ($hole->hole_number === $currentHole->hole_number)
                    active
                @elseif ($results->has($hole->id))
                    completed
                @endif"
        ></a>

    @endforeach

</div>


{{-- GROZA INFORMĀCIJA --}}

<div class="hole-info">

    <div class="hole-info-title">

        Grozs {{ $currentHole->hole_number }}

        |

        PAR {{ $currentHole->par }}

    </div>

    <div class="hole-info-subtitle">

        {{ $competition->course->name }}

    </div>

</div>


@php

    /*
     * Pašreizējā groza rezultāts
     */
    $result = $results->get($currentHole->id);

    /*
     * Pašreizējā groza rezultāts pret PAR
     */
    $relative = $result
        ? $result->score - $currentHole->par
        : 0;

    /*
     * Kopējie metieni tikai izspēlētajiem groziem
     */
    $totalScore = $results->sum('score');

    /*
     * Kopējais PAR tikai izspēlētajiem groziem
     */
    $totalPar = 0;

    foreach ($results as $playedResult) {

        $hole = $courseHoles->firstWhere(
            'id',
            $playedResult->course_hole_id
        );

        if ($hole) {
            $totalPar += $hole->par;
        }

    }

    /*
     * Kopā pret PAR
     */
    $totalRelative = $totalScore - $totalPar;

@endphp


{{-- SCORE CARD --}}

<div class="score-card">

    <form
        method="POST"
        action="{{ route(
            'competitions.scorecard.store',
            $competition
        ) }}"
    >

        @csrf

        <input
            type="hidden"
            name="course_hole_id"
            value="{{ $currentHole->id }}"
        >


        <div class="player-row">

            <div class="player-position">
                1.
            </div>

            <div class="player-name">
                {{ auth()->user()->name }}
            </div>

            <div class="penalty">
                PEN
            </div>

            <input
                type="number"
                name="score"
                class="score-input"
                min="1"
                max="100"
                value="{{ $result?->score }}"
                autofocus
                required
            >

            <div class="relative-score">

                @if ($result)

                    @if ($relative < 0)

                        <span class="under-par">
                            {{ $relative }}
                        </span>

                    @elseif ($relative > 0)

                        <span class="over-par">
                            +{{ $relative }}
                        </span>

                    @else

                        <span class="even-par">
                            E
                        </span>

                    @endif

                @else

                    <span class="even-par">
                        -
                    </span>

                @endif

            </div>

        </div>


        {{-- NAVIGĀCIJA --}}

        <div class="score-navigation">

            @if ($currentHole->hole_number > 1)

                <a
                    href="{{ route(
                        'competitions.scorecard',
                        [
                            'competition' => $competition,
                            'hole' => $currentHole->hole_number - 1
                        ]
                    ) }}"
                    class="nav-hole"
                >
                    ← Iepriekšējais
                </a>

            @else

                <div></div>

            @endif


            <button
                type="submit"
                class="save-next"
            >

                @if ($currentHole->hole_number < $courseHoles->count())

                    Saglabāt →

                @else

                    Pabeigt

                @endif

            </button>

        </div>

    </form>

</div>


{{-- KOPSAVILKUMS --}}

<div class="summary">

    <div class="summary-title">
        Mans rezultāts
    </div>

    <div class="summary-row">

        <span>
            Izspēlēti grozi
        </span>

        <strong>
            {{ $results->count() }}
            /
            {{ $courseHoles->count() }}
        </strong>

    </div>

    <div class="summary-row">

        <span>
            Metieni
        </span>

        <strong>
            {{ $totalScore }}
        </strong>

    </div>

    <div class="summary-row">

        <span>
            Pret PAR
        </span>

        <strong>

            @if ($results->count() === 0)

                -

            @elseif ($totalRelative > 0)

                +{{ $totalRelative }}

            @elseif ($totalRelative < 0)

                {{ $totalRelative }}

            @else

                E

            @endif

        </strong>

    </div>

</div>


</main>

</body>
</html>
