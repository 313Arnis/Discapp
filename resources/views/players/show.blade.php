<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $user->name }} - DiscGolf</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        .public-profile {
            max-width: 1000px;

            margin: 35px auto;

            padding: 0 20px;
        }


        /* PROFILA GALVENE */

        .public-profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 25px;

            padding: 25px;

            margin-bottom: 15px;

            background: white;

            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .public-profile-user {
            display: flex;
            align-items: center;

            gap: 18px;
        }

        .public-profile-picture {
            width: 90px;
            height: 90px;

            object-fit: cover;

            border-radius: 50%;

            border: 3px solid white;

            box-shadow:
                0 0 0 1px #ddd,
                0 3px 10px rgba(
                    0,
                    0,
                    0,
                    0.1
                );
        }

        .public-profile-avatar {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 90px;
            height: 90px;

            background: #eee;
            color: #333;

            border-radius: 50%;

            font-size: 34px;
            font-weight: bold;
        }

        .public-profile-name h1 {
            margin: 0 0 7px;

            font-size: 28px;
        }

        .public-profile-status {
            display: inline-block;

            padding: 4px 10px;

            background: #eee;
            color: #555;

            border-radius: 20px;

            font-size: 12px;
        }

        .public-profile-rating {
            min-width: 120px;

            padding-left: 30px;

            border-left: 1px solid #ddd;

            text-align: center;
        }

        .public-profile-rating span {
            display: block;

            margin-bottom: 3px;

            color: #777;

            font-size: 12px;
        }

        .public-profile-rating strong {
            display: block;

            font-size: 30px;
        }


        /* STATISTIKA */

        .public-profile-stats {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 12px;

            margin-bottom: 15px;
        }

        .public-stat {
            padding: 18px;

            background: white;

            border: 1px solid #ddd;
            border-radius: 8px;

            text-align: center;
        }

        .public-stat span {
            display: block;

            margin-bottom: 5px;

            color: #777;

            font-size: 13px;
        }

        .public-stat strong {
            font-size: 24px;
        }


        /* SACENSĪBU VĒSTURE */

        .public-section {
            overflow: hidden;

            background: white;

            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .public-section-header {
            padding: 15px 20px;

            background: #f8f8f8;

            border-bottom: 1px solid #ddd;
        }

        .public-section-header h2 {
            margin: 0;

            font-size: 19px;
        }

        .history-wrapper {
            overflow-x: auto;
        }

        .history-table {
            width: 100%;

            border-collapse: collapse;
        }

        .history-table th {
            padding: 12px 15px;

            background: #fafafa;

            color: #777;

            border-bottom: 1px solid #ddd;

            text-align: left;

            font-size: 12px;
        }

        .history-table td {
            padding: 14px 15px;

            border-bottom: 1px solid #eee;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }

        .history-table tbody tr:hover {
            background: #fafafa;
        }

        .competition-link {
            color: #222;

            font-weight: bold;

            text-decoration: none;
        }

        .competition-link:hover {
            text-decoration: underline;
        }

        .division-badge {
            display: inline-block;

            padding: 4px 8px;

            background: #eee;

            border-radius: 4px;

            font-size: 12px;
            font-weight: bold;
        }

        .history-empty {
            padding: 35px 20px;

            color: #777;

            text-align: center;
        }


        @media (max-width: 650px) {

            .public-profile {
                padding: 0 15px;
            }

            .public-profile-header {
                flex-direction: column;
                align-items: stretch;
            }

            .public-profile-picture,
            .public-profile-avatar {
                width: 75px;
                height: 75px;
            }

            .public-profile-avatar {
                font-size: 28px;
            }

            .public-profile-rating {
                display: flex;
                justify-content: space-between;
                align-items: center;

                width: 100%;

                padding: 15px 0 0;

                border-left: none;
                border-top: 1px solid #ddd;

                text-align: left;
            }

            .public-profile-stats {
                grid-template-columns: 1fr;
            }

            .history-table {
                min-width: 650px;
            }

        }

    </style>

</head>

<body>


<nav>

    <h2>
        DiscGolf
    </h2>

    <div>

        <a href="{{ route('home') }}">
            Sākums
        </a>

        <a href="{{ route('competitions.index') }}">
            Sacensības
        </a>

        <a href="{{ route('players.index') }}">
            Spēlētāji
        </a>

        @auth

            <a href="{{ route('profile') }}">
                Mans profils
            </a>

        @endauth

    </div>


    <div class="nav-auth">

        @auth

            <span>
                Sveiks, {{ auth()->user()->name }}!
            </span>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button type="submit">
                    Iziet
                </button>

            </form>

        @else

            <a href="{{ route('login') }}">
                Ieiet
            </a>

            <a href="{{ route('register') }}">
                Reģistrēties
            </a>

        @endauth

    </div>

</nav>


<main class="public-profile">


    {{-- PROFILA GALVENE --}}

    <div class="public-profile-header">

        <div class="public-profile-user">

            @if($user->profile_picture)

                <img
                    src="{{ asset(
                        'storage/' .
                        $user->profile_picture
                    ) }}"
                    alt="{{ $user->name }}"
                    class="public-profile-picture"
                >

            @else

                <div class="public-profile-avatar">

                    {{
                        strtoupper(
                            substr(
                                $user->name,
                                0,
                                1
                            )
                        )
                    }}

                </div>

            @endif


            <div class="public-profile-name">

                <h1>
                    {{ $user->name }}
                </h1>

                <span class="public-profile-status">
                    Spēlētājs
                </span>

            </div>

        </div>


        <div class="public-profile-rating">

            <span>
                Reitings
            </span>

            <strong>
                {{ $user->rating }}
            </strong>

        </div>

    </div>


    {{-- STATISTIKA --}}

    <div class="public-profile-stats">

        <div class="public-stat">

            <span>
                Sacensības
            </span>

            <strong>
                {{ $user->competitions->count() }}
            </strong>

        </div>


        <div class="public-stat">

            <span>
                Izspēlētas
            </span>

            <strong>
                {{ $playedCompetitions }}
            </strong>

        </div>


        <div class="public-stat">

            <span>
                Uzvaras
            </span>

            <strong>
                {{ $wins }}
            </strong>

        </div>

    </div>


    {{-- SACENSĪBU VĒSTURE --}}

    <div class="public-section">

        <div class="public-section-header">

            <h2>
                Sacensību vēsture
            </h2>

        </div>


        @if($competitionHistory->count() > 0)

            <div class="history-wrapper">

                <table class="history-table">

                    <thead>

                        <tr>

                            <th>
                                Sacensības
                            </th>

                            <th>
                                Datums
                            </th>

                            <th>
                                Divīzija
                            </th>

                            <th>
                                Rezultāts
                            </th>

                            <th>
                                Vieta
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach(
                            $competitionHistory
                            as $history
                        )

                            <tr>

                                <td>

                                    <a
                                        href="{{ route(
                                            'competitions.show',
                                            $history[
                                                'competition'
                                            ]
                                        ) }}"
                                        class="competition-link"
                                    >

                                        {{
                                            $history[
                                                'competition'
                                            ]->name
                                        }}

                                    </a>

                                </td>


                                <td>

                                    {{
                                        $history[
                                            'competition'
                                        ]->date
                                            ? \Carbon\Carbon::parse(
                                                $history[
                                                    'competition'
                                                ]->date
                                            )->format(
                                                'd.m.Y'
                                            )
                                            : '-'
                                    }}

                                </td>


                                <td>

                                    <span class="division-badge">

                                        {{
                                            $history[
                                                'division'
                                            ]
                                        }}

                                    </span>

                                </td>


                                <td>

                                    <strong>

                                        @if(
                                            $history[
                                                'relative_to_par'
                                            ] === null
                                        )

                                            -

                                        @elseif(
                                            $history[
                                                'relative_to_par'
                                            ] === 0
                                        )

                                            E

                                        @elseif(
                                            $history[
                                                'relative_to_par'
                                            ] > 0
                                        )

                                            +{{
                                                $history[
                                                    'relative_to_par'
                                                ]
                                            }}

                                        @else

                                            {{
                                                $history[
                                                    'relative_to_par'
                                                ]
                                            }}

                                        @endif

                                    </strong>

                                </td>


                                <td>

                                    <strong>

                                        {{
                                            $history[
                                                'place'
                                            ]
                                        }}.

                                    </strong>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="history-empty">

                Šis spēlētājs vēl nav pabeidzis
                nevienas sacensības.

            </div>

        @endif

    </div>

</main>

</body>
</html>