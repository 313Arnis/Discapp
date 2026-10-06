<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Practice - Discapp</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        body {
            background: #f7f7f4;
        }

        .practice-page {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 25px;
        }

        .practice-header {
            margin-bottom: 35px;
        }

        .practice-badge {
            display: inline-block;

            margin-bottom: 12px;
            padding: 7px 12px;

            background: #fff0e7;
            color: #d95f1f;

            border-radius: 50px;

            font-size: 11px;
            font-weight: 800;
            letter-spacing: .6px;
        }

        .practice-header h1 {
            margin: 0 0 10px;

            font-size: 38px;
        }

        .practice-header p {
            margin: 0;

            color: #777;
        }

        .practice-card {
            padding: 28px;

            background: white;

            border: 1px solid #e4e4df;
            border-radius: 18px;

            box-shadow:
                0 8px 30px
                rgba(0, 0, 0, .04);
        }

        .practice-card h2 {
            margin-top: 0;
        }

        .practice-form {
            width: 100% !important;
            max-width: none !important;

            margin: 0 !important;
            padding: 0 !important;

            border: none !important;

            background: transparent !important;
        }

        .practice-form label {
            display: block;

            margin-bottom: 8px;

            font-size: 14px;
            font-weight: 700;
        }

        .practice-form select {
            width: 100%;
            height: 50px;

            margin: 0 0 18px;
            padding: 0 14px;

            border: 1px solid #ddd;
            border-radius: 10px;

            background: white;

            font-size: 15px;
        }

        .practice-form button {
            width: auto !important;

            margin: 0 !important;
            padding: 13px 24px !important;

            border: none !important;
            border-radius: 10px !important;

            background: #e86f2d !important;
            color: white !important;

            font-weight: 700;

            cursor: pointer;
        }

        .active-round {
            margin-bottom: 25px;
            padding: 22px;

            background: #fff7f1;

            border:
                1px solid
                rgba(232, 111, 45, .25);

            border-radius: 14px;
        }

        .active-round h3 {
            margin: 0 0 8px;
        }

        .active-round p {
            margin: 0 0 15px;

            color: #666;
        }

        .continue-button {
            display: inline-block;

            padding: 11px 18px;

            background: #e86f2d;
            color: white;

            border-radius: 9px;

            font-weight: 700;
            text-decoration: none;
        }

    </style>

</head>

<body>

<nav>

    <h2>
        Discapp
    </h2>

    <div>

        <a href="/">
            Sākums
        </a>

        <a href="{{ route('competitions.index') }}">
            Sacensības
        </a>

        <a href="{{ route('players.index') }}">
            Spēlētāji
        </a>

        <a href="{{ route('profile') }}">
            Mans profils
        </a>

    </div>

</nav>


<main class="practice-page">

    <div class="practice-header">

        <span class="practice-badge">
            PRACTICE
        </span>

        <h1>
            Practice aplis
        </h1>

        <p>
            Izvēlies trasi un sāc treniņa apli.
        </p>

    </div>


    @if($activeRound)

        <div class="active-round">

            <h3>
                Tev ir iesākts aplis
            </h3>

            <p>
                {{ $activeRound->course->name }}
            </p>

            <a
                href="{{ route(
                    'practice.scorecard',
                    $activeRound
                ) }}"
                class="continue-button"
            >
                Turpināt apli
            </a>

        </div>

    @endif


    <div class="practice-card">

        <h2>
            Izvēlies trasi
        </h2>


        @if($errors->any())

            <div class="error">

                {{ $errors->first() }}

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('practice.store') }}"
            class="practice-form"
        >

            @csrf


            <label for="course_id">
                Trase
            </label>


            <select
                id="course_id"
                name="course_id"
                required
            >

                <option value="">
                    Izvēlies trasi
                </option>


                @foreach($courses as $course)

                    <option
                        value="{{ $course->id }}"
                    >
                        {{ $course->name }}
                        —
                        {{ $course->courseHoles->count() }}
                        grozi
                    </option>

                @endforeach

            </select>


            <button type="submit">
                Sākt Practice apli
            </button>

        </form>

    </div>

</main>

</body>
</html>