<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Spēlētāji - DiscGolf</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        .players-page {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .players-header {
            margin-bottom: 25px;
        }

        .players-header h1 {
            margin-bottom: 5px;
        }

        .players-header p {
            margin: 0;
            color: #777;
        }


        /* MEKLĒŠANA */

        .player-search {
            display: flex;

            width: 100%;
            max-width: none;

            margin: 0 0 25px;
            padding: 0;

            background: transparent;

            border: none;
            box-shadow: none;
        }

        .player-search input {
            flex: 1;

            min-width: 0;

            padding: 12px 15px;

            border: 1px solid #ddd;
            border-right: none;

            border-radius: 7px 0 0 7px;

            font-size: 15px;
        }

        .player-search input:focus {
            outline: none;

            border-color: #999;
        }

        .player-search button {
            width: auto;

            margin: 0;

            padding: 12px 22px;

            background: #222;
            color: white;

            border: 1px solid #222;

            border-radius: 0 7px 7px 0;

            cursor: pointer;

            font-weight: bold;
        }

        .player-search button:hover {
            background: #444;
        }


        /* SPĒLĒTĀJI */

        .players-grid {
            display: grid;

            grid-template-columns:
                repeat(
                    auto-fill,
                    minmax(250px, 1fr)
                );

            gap: 15px;
        }

        .player-card {
            display: flex;
            flex-direction: column;

            padding: 20px;

            background: white;

            border: 1px solid #ddd;
            border-radius: 10px;

            transition: 0.2s ease;
        }

        .player-card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 5px 15px rgba(
                    0,
                    0,
                    0,
                    0.06
                );
        }

        .player-card-top {
            display: flex;
            align-items: center;

            gap: 14px;

            margin-bottom: 18px;
        }

        .player-card-picture {
            width: 60px;
            height: 60px;

            flex-shrink: 0;

            object-fit: cover;

            border-radius: 50%;

            border: 2px solid white;

            box-shadow:
                0 0 0 1px #ddd;
        }

        .player-card-avatar {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 60px;
            height: 60px;

            flex-shrink: 0;

            background: #eee;
            color: #333;

            border-radius: 50%;

            font-size: 24px;
            font-weight: bold;
        }

        .player-card-name {
            min-width: 0;
        }

        .player-card-name h2 {
            margin: 0 0 4px;

            font-size: 18px;
        }

        .player-card-rating {
            color: #777;

            font-size: 13px;
        }

        .player-card-stats {
            display: flex;

            gap: 10px;

            margin-bottom: 18px;
        }

        .player-card-stat {
            flex: 1;

            padding: 10px;

            background: #f8f8f8;

            border-radius: 6px;

            text-align: center;
        }

        .player-card-stat span {
            display: block;

            margin-bottom: 3px;

            color: #888;

            font-size: 11px;
        }

        .player-card-stat strong {
            font-size: 17px;
        }

        .player-profile-link {
            display: block;

            margin-top: auto;

            padding: 9px 12px;

            background: #222;
            color: white;

            border-radius: 6px;

            text-align: center;
            text-decoration: none;

            font-size: 13px;
            font-weight: bold;
        }

        .player-profile-link:hover {
            background: #444;
        }

        .players-empty {
            padding: 40px 20px;

            background: white;

            border: 1px solid #ddd;
            border-radius: 8px;

            text-align: center;

            color: #777;
        }

        .players-pagination {
            margin-top: 25px;
        }


        @media (max-width: 600px) {

            .players-page {
                padding: 0 15px;
            }

            .players-grid {
                grid-template-columns: 1fr;
            }

            .player-search button {
                padding-left: 15px;
                padding-right: 15px;
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


<main class="players-page">


    <div class="players-header">

        <h1>
            Spēlētāji
        </h1>

        <p>
            Atrodi DiscGolf spēlētājus un apskati
            viņu rezultātus.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('players.index') }}"
        class="player-search"
    >

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Meklēt spēlētāju pēc vārda..."
        >

        <button type="submit">
            Meklēt
        </button>

    </form>


    @if($players->count() > 0)

        <div class="players-grid">

            @foreach($players as $player)

                <div class="player-card">


                    <div class="player-card-top">

                        @if($player->profile_picture)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $player->profile_picture
                                ) }}"
                                alt="{{ $player->name }}"
                                class="player-card-picture"
                            >

                        @else

                            <div class="player-card-avatar">

                                {{
                                    strtoupper(
                                        substr(
                                            $player->name,
                                            0,
                                            1
                                        )
                                    )
                                }}

                            </div>

                        @endif


                        <div class="player-card-name">

                            <h2>
                                {{ $player->name }}
                            </h2>

                            <div class="player-card-rating">

                                Reitings:
                                <strong>
                                    {{ $player->rating }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="player-card-stats">

                        <div class="player-card-stat">

                            <span>
                                Izspēlētas
                            </span>

                            <strong>
                                {{
                                    $player
                                        ->played_competitions_count
                                }}
                            </strong>

                        </div>

                    </div>


                    <a
                        href="{{ route(
                            'players.show',
                            $player
                        ) }}"
                        class="player-profile-link"
                    >
                        Skatīt profilu
                    </a>

                </div>

            @endforeach

        </div>


        <div class="players-pagination">
            {{ $players->links() }}
        </div>

    @else

        <div class="players-empty">

            @if($search)

                Neviens spēlētājs ar vārdu
                <strong>
                    "{{ $search }}"
                </strong>
                netika atrasts.

            @else

                Spēlētāji nav atrasti.

            @endif

        </div>

    @endif

</main>

</body>
</html>