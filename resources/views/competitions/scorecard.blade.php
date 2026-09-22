<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $competition->name }} - Rezultāts
    </title>

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

        /* TOP */

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

        /* HOLE INDICATORS */

        .hole-indicators {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin: 20px 0 8px;
            flex-wrap: wrap;
        }

        .hole-indicator {
            width: 13px;
            height: 13px;
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
            background: #bfc5c3;
        }

        /* HOLE INFO */

        .hole-info {
            text-align: center;
            margin-bottom: 22px;
        }

        .hole-info-title {
            font-size: 18px;
            font-style: italic;
            color: #222;
        }

        .hole-info-subtitle {
            margin-top: 3px;
            color: #555;
            font-size: 15px;
        }

        /* SCORE CARD */

        .score-card {
            border-top: 1px solid #ddd;
            background: transparent;
        }

        .player-row {
            min-height: 62px;
            border-bottom: 1px solid #ddd;

            display: flex;
            align-items: center;

            gap: 16px;
        }

        .player-position {
            width: 30px;
            text-align: right;
            font-size: 18px;
        }

        .player-name {
            flex: 1;
            font-size: 19px;
        }

        .penalty {
            color: #d9dddd;
            font-size: 18px;
            font-weight: bold;
        }

        .score-input {
            width: 56px;
            height: 56px;

            border: 2px solid #ff6500;
            border-radius: 2px;

            background: #fffdf9;

            font-size: 25px;
            text-align: center;

            outline: none;
        }

        .score-input:focus {
            border-color: #222;
        }

        .relative-score {
            width: 30px;
            font-size: 18px;
            text-align: center;
        }

        /* NAVIGATION */

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
            min-width: 120px;

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

        /* SUMMARY */

        .summary {
            margin-top: 30px;

            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;

            padding: 18px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;

            padding: 5px 0;

            color: #555;
        }

        .summary-row strong {
            color: #222;
        }

        /* MESSAGE */

        .success-message {
            background: #e9f7e8;
            color: #287a25;

            padding: 11px 15px;

            border-radius: 5px;

            margin-bottom: 15px;

            text-align: center;
        }

        /* MOBILE */

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
                font-size: 14px;
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


    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    {{-- 18 GROZU INDIKATORI --}}

    <div class="hole-indicators">

        @foreach($courseHoles as $hole)

            <a
                href="{{ route(
                    'competitions.scorecard',
                    [
                        'competition' => $competition,
                        'hole' => $hole->hole_number
                    ]
                ) }}"
                class="hole-indicator
                    @if($hole->hole_number === $currentHole->hole_number)
                        active
                    @elseif($results->has($hole->id))
                        completed
                    @endif"
            ></a>

        @endforeach

    </div>


    {{-- GROZA INFORMĀCIJA --}}

    <div class="hole-info">

        <div class="hole-info-title">

            Basket no {{ $currentHole->hole_number }}
            |
            par {{ $currentHole->par }}

        </div>

        <div class="hole-info-subtitle">

            {{ $competition->course->name }}

        </div>

    </div>


    {{-- REZULTĀTA FORMA --}}

    @php

        $result = $results->get($currentHole->id);

        $relative = $result
            ? $result->score - $currentHole->par
            : 0;

        $playedResults = $results->values();

        $totalScore = $playedResults->sum('score');

        $totalPar = $playedResults->sum(function ($item) use ($courseHoles) {

            $hole = $courseHoles->firstWhere(
                'id',
                $item->course_hole_id
            );

            return $hole ? $hole->par : 0;

        });

        $totalRelative = $totalScore - $totalPar;

    @endphp


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

                    @if($result)

                        @if($relative > 0)
                            +{{ $relative }}
                        @elseif($relative < 0)
                            {{ $relative }}
                        @else
                            0
                        @endif

                    @else
                        0
                    @endif

                </div>

            </div>


            {{-- NAVIGĀCIJA --}}

            <div class="score-navigation">


                {{-- IEPRIEKŠĒJAIS --}}

                @if($currentHole->hole_number > 1)

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


                {{-- NĀKAMAIS / SAGLABĀT --}}

                <button
                    type="submit"
                    class="save-next"
                >

                    @if($currentHole->hole_number < $courseHoles->count())
                        Saglabāt →
                    @else
                        Saglabāt
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

                @if($totalRelative > 0)
                    +{{ $totalRelative }}
                @elseif($totalRelative < 0)
                    {{ $totalRelative }}
                @else
                    0
                @endif

            </strong>

        </div>

    </div>


</main>


</body>

</html>