<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Spēlētāji - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f7f4;
            color: #202020;
        }


        /* =========================
           NAVBAR
        ========================= */

        .players-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;

            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;

            background: transparent;
        }

        .players-logo {
            color: #e86f2d;
            font-size: 24px;
            font-weight: 800;
            text-decoration: none;
        }

        .players-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .players-nav-links a {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .players-nav-links a:hover,
        .players-nav-links .active {
            color: #e86f2d;
        }

        .players-nav-auth {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .players-nav-auth span {
            color: #666;
            font-size: 14px;
        }

        .players-nav-auth form {
            margin: 0;
        }

        .nav-login {
            color: #333;
            font-weight: 600;
            text-decoration: none;
        }

        .nav-register,
        .nav-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 10px 18px;

            border: none;
            border-radius: 10px;

            background: #e86f2d;
            color: white;

            font-weight: 700;
            text-decoration: none;
            cursor: pointer;

            transition: 0.2s;
        }

        .nav-register:hover,
        .nav-logout:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }


        /* =========================
           PAGE
        ========================= */

        .players-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 30px 80px;
        }


        /* =========================
           HEADER
        ========================= */

        .players-header {
            margin-bottom: 30px;
        }

        .players-badge {
            display: inline-block;

            margin-bottom: 12px;
            padding: 7px 13px;

            border-radius: 50px;

            background: #fff0e7;
            color: #d95f1f;

            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .players-header h1 {
            margin: 0 0 12px;

            font-size: clamp(36px, 5vw, 52px);
            line-height: 1.05;
            letter-spacing: -2px;
        }

        .players-header p {
            max-width: 600px;

            margin: 0;

            color: #777;

            font-size: 16px;
            line-height: 1.6;
        }


        /* =========================
           SEARCH
        ========================= */

        .player-search-wrapper {
            max-width: 720px;
            margin-bottom: 38px;
        }

        .player-search {
            display: flex;
            align-items: center;

            width: 100%;
            height: 56px;

            margin: 0;
            padding: 5px;

            border: 1px solid #deded9;
            border-radius: 14px;

            background: white;

            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.04);

            transition: 0.2s;
        }

        .player-search:focus-within {
            border-color: #e86f2d;
            box-shadow: 0 0 0 3px rgba(232, 111, 45, 0.1);
        }

        .search-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 42px;
            height: 42px;

            flex-shrink: 0;

            color: #999;
        }

        .search-icon svg {
            width: 20px;
            height: 20px;
        }

        .player-search input {
            flex: 1;
            min-width: 0;
            height: 100%;

            padding: 0 8px;

            border: none;
            outline: none;

            background: transparent;
            color: #222;

            font-family: inherit;
            font-size: 15px;
        }

        .player-search input::placeholder {
            color: #aaa;
        }

        .player-search button {
            display: flex;
            align-items: center;
            justify-content: center;

            width: auto;
            height: 44px;

            margin: 0;
            padding: 0 22px;

            border: none;
            border-radius: 10px;

            background: #e86f2d;
            color: white;

            font-family: inherit;
            font-size: 14px;
            font-weight: 700;

            cursor: pointer;

            transition: 0.2s;
        }

        .player-search button:hover {
            background: #d96124;
        }


        /* =========================
           PLAYER GRID
        ========================= */

        .players-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fill, minmax(280px, 1fr));

            gap: 18px;
        }


        /* =========================
           PLAYER CARD
        ========================= */

        .player-card {
            display: flex;
            flex-direction: column;

            padding: 24px;

            border: 1px solid #e4e4df;
            border-radius: 18px;

            background: white;

            transition: 0.2s;
        }

        .player-card:hover {
            transform: translateY(-4px);

            border-color: #ddd;

            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        }

        .player-card-top {
            display: flex;
            align-items: center;
            gap: 15px;

            margin-bottom: 22px;
        }


        /* =========================
           PROFILE PICTURE
        ========================= */

        .player-card-picture {
            width: 64px;
            height: 64px;

            flex-shrink: 0;

            border: 3px solid white;
            border-radius: 50%;

            object-fit: cover;

            box-shadow: 0 0 0 1px #ddd;
        }

        .player-card-avatar {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 64px;
            height: 64px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #fff0e7;
            color: #e86f2d;

            font-size: 24px;
            font-weight: 800;
        }

        .player-card-name {
            min-width: 0;
        }

        .player-card-name h2 {
            margin: 0 0 5px;

            color: #222;

            font-size: 19px;

            overflow-wrap: anywhere;
        }


        /* =========================
           RATING
        ========================= */

        .player-card-rating {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            color: #777;

            font-size: 13px;
        }

        .player-card-rating strong {
            color: #e86f2d;
            font-size: 15px;
        }


        /* =========================
           STATS
        ========================= */

        .player-card-stats {
            display: flex;
            gap: 10px;

            margin-bottom: 20px;
        }

        .player-card-stat {
            flex: 1;

            padding: 13px 10px;

            border-radius: 10px;

            background: #f7f7f4;

            text-align: center;
        }

        .player-card-stat span {
            display: block;

            margin-bottom: 4px;

            color: #888;

            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .player-card-stat strong {
            color: #222;
            font-size: 19px;
        }


        /* =========================
           PROFILE BUTTON
        ========================= */

        .player-profile-link {
            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 43px;

            margin-top: auto;
            padding: 0 14px;

            border: 1px solid #ddd;
            border-radius: 10px;

            background: white;
            color: #333;

            font-size: 13px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;

            transition: 0.2s;
        }

        .player-profile-link:hover {
            border-color: #e86f2d;

            background: #fff8f4;
            color: #e86f2d;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .players-empty {
            padding: 60px 30px;

            border: 1px dashed #d7d7d2;
            border-radius: 18px;

            background: white;

            color: #777;
            text-align: center;
        }

        .players-empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 65px;
            height: 65px;

            margin: 0 auto 18px;

            border-radius: 18px;

            background: #fff0e7;

            font-size: 28px;
        }

        .players-empty h2 {
            margin: 0 0 8px;

            color: #222;
            font-size: 21px;
        }

        .players-empty p {
            margin: 0;
        }


        /* =========================
           PAGINATION
        ========================= */

        .players-pagination {
            margin-top: 35px;
        }


        /* =========================
           FOOTER
        ========================= */

        .players-footer {
            margin-top: 70px;
            padding-top: 25px;

            border-top: 1px solid #ddd;

            color: #999;
            font-size: 13px;
            text-align: center;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 850px) {

            .players-nav {
                flex-wrap: wrap;
            }

            .players-nav-links {
                order: 3;

                width: 100%;

                justify-content: center;
            }
        }


        @media (max-width: 600px) {

            .players-nav {
                padding: 18px 20px;
            }

            .players-page {
                padding: 45px 20px 60px;
            }

            .players-nav-links {
                gap: 15px;
            }

            .players-nav-auth span {
                display: none;
            }

            .players-header h1 {
                font-size: 38px;
                letter-spacing: -1px;
            }

            .players-grid {
                grid-template-columns: 1fr;
            }

            .player-search-wrapper {
                max-width: 100%;
            }

            .player-search {
                height: 54px;
            }

            .search-icon {
                width: 36px;
            }

            .player-search button {
                padding: 0 15px;
            }

            .player-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<nav class="players-nav">

    <a
        href="{{ route('home') }}"
        class="players-logo"
    >
        Discapp
    </a>


    <div class="players-nav-links">

        <a href="{{ route('home') }}">
            Sākums
        </a>

        <a href="{{ route('competitions.index') }}">
            Sacensības
        </a>

        <a
            href="{{ route('players.index') }}"
            class="active"
        >
            Spēlētāji
        </a>

        @auth

            <a href="{{ route('profile') }}">
                Mans profils
            </a>

        @endauth

    </div>


    <div class="players-nav-auth">

        @auth

            <span>
                Sveiks,
                {{ auth()->user()->name }}
                {{ auth()->user()->surname }}!
            </span>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="nav-logout"
                >
                    Iziet
                </button>

            </form>

        @else

            <a
                href="{{ route('login') }}"
                class="nav-login"
            >
                Pieslēgties
            </a>

            <a
                href="{{ route('register') }}"
                class="nav-register"
            >
                Reģistrēties
            </a>

        @endauth

    </div>

</nav>


<main class="players-page">

    <section class="players-header">

        <span class="players-badge">
            SPĒLĒTĀJI
        </span>

        <h1>
            Atrodi spēlētājus
        </h1>

        <p>
            Meklē Discapp spēlētājus, apskati viņu
            reitingu, sacensību rezultātus un profilu.
        </p>

    </section>


    <div class="player-search-wrapper">

        <form
            method="GET"
            action="{{ route('players.index') }}"
            class="player-search"
        >

            <div class="search-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <line
                        x1="16.5"
                        y1="16.5"
                        x2="21"
                        y2="21"
                    ></line>
                </svg>

            </div>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Meklēt pēc vārda vai uzvārda..."
                autocomplete="off"
            >

            <button type="submit">
                Meklēt
            </button>

        </form>

    </div>


    @if($players->count() > 0)

        <section class="players-grid">

            @foreach($players as $player)

                <article class="player-card">

                    <div class="player-card-top">

                        @if($player->profile_picture)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $player->profile_picture
                                ) }}"
                                alt="{{ $player->name }} {{ $player->surname }}"
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
                                {{ $player->surname }}
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
                        Skatīt profilu →
                    </a>

                </article>

            @endforeach

        </section>


        <div class="players-pagination">
            {{ $players->links() }}
        </div>

    @else

        <div class="players-empty">

            <div class="players-empty-icon">
                👤
            </div>

            @if($search)

                <h2>
                    Spēlētājs netika atrasts
                </h2>

                <p>
                    Neviens spēlētājs ar meklējumu
                    <strong>
                        "{{ $search }}"
                    </strong>
                    netika atrasts.
                </p>

            @else

                <h2>
                    Spēlētāju vēl nav
                </h2>

                <p>
                    Reģistrētie Discapp spēlētāji
                    parādīsies šeit.
                </p>

            @endif

        </div>

    @endif


    <footer class="players-footer">
        Discapp — disku golfa sacensību un rezultātu pārvaldības sistēma.
    </footer>

</main>

</body>
</html>