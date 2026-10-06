<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $practiceRound->course->name }} - Practice
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        body {
            background: #f7f7f4;
        }


        .scorecard-page {
            max-width: 730px;
            margin: 30px auto;
            padding: 0 20px;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

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


        .practice-button {
            padding: 11px 24px;

            border: none;
            border-radius: 4px;

            background: #e86f2d;
            color: white;

            font-size: 16px;
            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | GROZU INDIKATORI
        |--------------------------------------------------------------------------
        */

        .hole-indicators {
            display: flex;
            justify-content: center;

            gap: 7px;

            margin: 20px 0 10px;

            flex-wrap: wrap;
        }


        .hole-indicator {
            display: block;

            width: 14px;
            height: 14px;

            border-radius: 50%;

            background: #d9dedd;

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


        /*
        |--------------------------------------------------------------------------
        | GROZA INFORMĀCIJA
        |--------------------------------------------------------------------------
        */

        .hole-info {
            margin-bottom: 22px;

            text-align: center;
        }


        .hole-info-title {
            color: #222;

            font-size: 20px;
            font-weight: bold;
        }


        .hole-info-subtitle {
            margin-top: 5px;

            color: #555;

            font-size: 15px;
        }


        /*
        |--------------------------------------------------------------------------
        | SCORECARD
        |--------------------------------------------------------------------------
        */

        .score-card {
            border-top: 1px solid #ddd;

            background: transparent;
        }


        .player-row {
            display: flex;
            align-items: center;

            min-height: 70px;

            gap: 16px;

            border-bottom: 1px solid #ddd;
        }


        .player-position {
            width: 30px;

            color: #777;

            font-size: 18px;
            text-align: right;
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


        /*
        |--------------------------------------------------------------------------
        | SCORE INPUT
        |--------------------------------------------------------------------------
        */

        .practice-score-form {
            width: 100% !important;
            max-width: none !important;

            margin: 0 !important;
            padding: 0 !important;

            border: none !important;

            background: transparent !important;
        }


        .score-input {
            width: 120px !important;
            height: 60px;

            box-sizing: border-box;

            margin: 0 !important;
            padding: 0 !important;

            border: 2px solid #ff6500 !important;
            border-radius: 6px !important;

            background: #fffdf9 !important;

            font-size: 25px !important;
            font-weight: 700;

            text-align: center;

            outline: none;
        }


        .score-input:focus {
            border-color: #222 !important;

            outline: none;
            box-shadow: none !important;
        }


        .score-input.saving {
            opacity: 0.6;
        }


        .relative-score {
            width: 45px;

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


        /*
        |--------------------------------------------------------------------------
        | AUTOSAVE
        |--------------------------------------------------------------------------
        */

        .autosave-info {
            margin-top: 12px;

            color: #777;

            font-size: 13px;

            text-align: right;
        }


        /*
        |--------------------------------------------------------------------------
        | NAVIGĀCIJA
        |--------------------------------------------------------------------------
        */

        .score-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-top: 22px;
        }


        .nav-hole {
            min-width: 100px;

            padding: 11px 18px;

            border: 1px solid #ddd;
            border-radius: 4px;

            background: white;

            color: #222;

            font-weight: bold;
            text-align: center;
            text-decoration: none;
        }


        .nav-hole:hover {
            background: #eee;
        }


        /*
        |--------------------------------------------------------------------------
        | KOPSAVILKUMS
        |--------------------------------------------------------------------------
        */

        .summary {
            margin-top: 30px;

            padding: 18px;

            border: 1px solid #ddd;
            border-radius: 6px;

            background: white;
        }


        .summary-title {
            margin-bottom: 12px;

            font-size: 17px;
            font-weight: bold;
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


        /*
        |--------------------------------------------------------------------------
        | FINISH
        |--------------------------------------------------------------------------
        */

        .finish-area {
            margin-top: 25px;

            text-align: center;
        }


        .finish-form {
            width: auto !important;
            max-width: none !important;

            margin: 0 !important;
            padding: 0 !important;

            border: none !important;

            background: transparent !important;
        }


        .finish-button {
            width: auto !important;

            margin: 0 !important;
            padding: 13px 25px !important;

            border: none !important;
            border-radius: 6px !important;

            background: #e86f2d !important;
            color: white !important;

            font-size: 15px;
            font-weight: bold;

            cursor: pointer;
        }


        .finish-button:hover {
            background: #d85e22 !important;
        }


        .finish-message {
            margin-bottom: 15px;

            color: #2e9d27;

            font-size: 15px;
            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        .practice-error {
            margin-bottom: 20px;
            padding: 12px 15px;

            border: 1px solid #f1c0bb;
            border-radius: 6px;

            background: #fff2f0;
            color: #b63b2f;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILĀ VERSIJA
        |--------------------------------------------------------------------------
        */

        @media (max-width: 600px) {

            .scorecard-page {
                margin-top: 15px;
                padding: 0 10px;
            }


            .scorecard-header h1 {
                font-size: 23px;
            }


            .practice-button {
                padding: 9px 15px;

                font-size: 13px;
            }


            .player-row {
                gap: 8px;
            }


            .player-name {
                font-size: 15px;
            }


            .score-input {
                width: 85px !important;
                height: 52px;

                font-size: 22px !important;
            }


            .penalty {
                display: none;
            }


            .relative-score {
                width: 35px;
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

        <a href="{{ route('practice.index') }}">
            Practice
        </a>

        <a href="{{ route('profile') }}">
            Profils
        </a>

    </div>

</nav>



<main class="scorecard-page">


    {{-- =====================================================
         ERROR
    ===================================================== --}}

    @if($errors->any())

        <div class="practice-error">

            {{ $errors->first() }}

        </div>

    @endif



    {{-- =====================================================
         HEADER
    ===================================================== --}}

    <div class="scorecard-header">

        <h1>
            {{ $practiceRound->course->name }}
        </h1>

        <div class="practice-button">
            Practice
        </div>

    </div>



    {{-- =====================================================
         GROZU INDIKATORI
    ===================================================== --}}

    <div class="hole-indicators">

        @foreach($holes as $hole)

            @php

                $completed =
                    $results->has(
                        $hole->id
                    );

                $active =
                    $currentHole &&
                    $hole->id ===
                    $currentHole->id;

            @endphp


            <span
                class="
                    hole-indicator

                    @if($active)
                        active
                    @elseif($completed)
                        completed
                    @endif
                "
                title="Grozs {{ $hole->hole_number }}"
            ></span>

        @endforeach

    </div>



    {{-- =====================================================
         GROZA INFORMĀCIJA
    ===================================================== --}}

    @if(!$isComplete && $currentHole)

        <div class="hole-info">

            <div class="hole-info-title">

                Grozs
                {{ $currentHole->hole_number }}

                |

                PAR
                {{ $currentHole->par }}

            </div>


            <div class="hole-info-subtitle">

                {{ $practiceRound->course->name }}

                · Practice aplis

            </div>

        </div>

    @else

        <div class="hole-info">

            <div class="hole-info-title">
                Visi grozi izspēlēti
            </div>

            <div class="hole-info-subtitle">
                {{ $practiceRound->course->name }}
            </div>

        </div>

    @endif



    {{-- =====================================================
         SCORECARD
    ===================================================== --}}

    @if(!$isComplete && $currentHole)

        @php

            $result =
                $results->get(
                    $currentHole->id
                );

            $relative =
                $result
                    ? $result->score -
                        $currentHole->par
                    : 0;

        @endphp


        <div class="score-card">

            <form
                id="scoreForm"
                method="POST"
                action="{{ route(
                    'practice.scorecard.store',
                    $practiceRound
                ) }}"
                class="practice-score-form"
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

                        @if(auth()->user()->surname)

                            {{ auth()->user()->surname }}

                        @endif

                    </div>


                    <div class="penalty">
                        PEN
                    </div>


                    <input
                        type="number"
                        id="scoreInput"
                        name="score"
                        class="score-input"

                        min="1"
                        max="20"

                        value="{{ $result?->score }}"

                        placeholder="Rezultāts"

                        inputmode="numeric"

                        autofocus
                        required
                    >


                    <div
                        class="relative-score"
                        id="relativeScore"
                    >

                        @if($result)

                            @if($relative < 0)

                                <span class="under-par">
                                    {{ $relative }}
                                </span>

                            @elseif($relative > 0)

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


                <div class="autosave-info">

                    Ieraksti rezultātu —
                    tas saglabāsies automātiski.

                </div>

            </form>

        </div>

    @endif



    {{-- =====================================================
         KOPSAVILKUMS
    ===================================================== --}}

    <div class="summary">

        <div class="summary-title">
            Mans Practice rezultāts
        </div>


        <div class="summary-row">

            <span>
                Trase
            </span>

            <strong>
                {{ $practiceRound->course->name }}
            </strong>

        </div>


        <div class="summary-row">

            <span>
                Izspēlēti grozi
            </span>

            <strong>

                {{ $practiceRound->holeResults->count() }}

                /

                {{ $holes->count() }}

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

                @if($practiceRound->holeResults->count() === 0)

                    -

                @elseif($relativeToPar > 0)

                    +{{ $relativeToPar }}

                @elseif($relativeToPar < 0)

                    {{ $relativeToPar }}

                @else

                    E

                @endif

            </strong>

        </div>

    </div>



    {{-- =====================================================
         APLIS PABEIGTS
    ===================================================== --}}

    @if($isComplete)

        <div class="finish-area">

            <div class="finish-message">
                Visi grozi ir aizpildīti!
            </div>


            <form
                method="POST"
                action="{{ route(
                    'practice.finish',
                    $practiceRound
                ) }}"
                class="finish-form"
            >

                @csrf


                <button
                    type="submit"
                    class="finish-button"
                >
                    Pabeigt Practice apli
                </button>

            </form>

        </div>

    @endif


</main>



@if(!$isComplete && $currentHole)

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const scoreForm =
                document.getElementById(
                    'scoreForm'
                );


            const scoreInput =
                document.getElementById(
                    'scoreInput'
                );


            const relativeScore =
                document.getElementById(
                    'relativeScore'
                );


            const par =
                {{ (int) $currentHole->par }};


            let saveTimer = null;

            let isSubmitting = false;



            /*
            |--------------------------------------------------------------------------
            | PRET PAR ATJAUNINĀŠANA
            |--------------------------------------------------------------------------
            */

            function updateRelativeScore() {

                const score =
                    parseInt(
                        scoreInput.value,
                        10
                    );


                if (
                    Number.isNaN(score)
                ) {

                    relativeScore.innerHTML =
                        '<span class="even-par">-</span>';

                    return;
                }


                const relative =
                    score - par;


                if (relative < 0) {

                    relativeScore.innerHTML =
                        '<span class="under-par">' +
                        relative +
                        '</span>';

                } else if (relative > 0) {

                    relativeScore.innerHTML =
                        '<span class="over-par">+' +
                        relative +
                        '</span>';

                } else {

                    relativeScore.innerHTML =
                        '<span class="even-par">E</span>';

                }

            }



            /*
            |--------------------------------------------------------------------------
            | AUTOMĀTISKĀ SAGLABĀŠANA
            |--------------------------------------------------------------------------
            */

            function scheduleSave() {

                clearTimeout(
                    saveTimer
                );


                const score =
                    parseInt(
                        scoreInput.value,
                        10
                    );


                if (
                    Number.isNaN(score) ||
                    score < 1 ||
                    score > 20
                ) {
                    return;
                }


                saveTimer =
                    setTimeout(
                        function () {

                            if (isSubmitting) {
                                return;
                            }


                            isSubmitting = true;


                            scoreInput
                                .classList
                                .add(
                                    'saving'
                                );


                            scoreForm.submit();

                        },
                        650
                    );

            }



            /*
            |--------------------------------------------------------------------------
            | INPUT
            |--------------------------------------------------------------------------
            */

            scoreInput.addEventListener(
                'input',
                function () {

                    updateRelativeScore();

                    scheduleSave();

                }
            );



            /*
            |--------------------------------------------------------------------------
            | ENTER
            |--------------------------------------------------------------------------
            */

            scoreInput.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Enter'
                    ) {

                        event.preventDefault();


                        clearTimeout(
                            saveTimer
                        );


                        const score =
                            parseInt(
                                scoreInput.value,
                                10
                            );


                        if (
                            Number.isNaN(score) ||
                            score < 1 ||
                            score > 20
                        ) {
                            return;
                        }


                        if (!isSubmitting) {

                            isSubmitting = true;

                            scoreForm.submit();

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | FOCUS
            |--------------------------------------------------------------------------
            */

            scoreInput.focus();

            scoreInput.select();

        }
    );

</script>

@endif


</body>
</html>