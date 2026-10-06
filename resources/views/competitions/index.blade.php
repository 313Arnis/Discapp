<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sacensības - Discapp</title>

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

        .competitions-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;

            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;

            background: transparent;
        }

        .competitions-logo {
            font-size: 24px;
            font-weight: 800;
            color: #e86f2d;
            text-decoration: none;
        }

        .competitions-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .competitions-nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .competitions-nav-links a:hover,
        .competitions-nav-links .active {
            color: #e86f2d;
        }

        .competitions-nav-auth {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .competitions-nav-auth span {
            color: #666;
            font-size: 14px;
        }

        .nav-login {
            color: #333;
            text-decoration: none;
            font-weight: 600;
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

        .competitions-nav-auth form {
            margin: 0;
        }


        /* =========================
           PAGE
        ========================= */

        .competitions-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 30px 80px;
        }


        /* =========================
           HEADER
        ========================= */

        .competitions-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 30px;

            margin-bottom: 40px;
        }

        .competitions-header-text {
            max-width: 650px;
        }

        .page-badge {
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

        .competitions-header h1 {
            margin: 0 0 12px;

            font-size: clamp(36px, 5vw, 52px);
            line-height: 1.05;
            letter-spacing: -2px;
        }

        .competitions-header p {
            margin: 0;

            color: #777;

            font-size: 16px;
            line-height: 1.6;
        }

        .create-competition-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 48px;
            padding: 0 20px;

            border-radius: 10px;

            background: #e86f2d;
            color: white;

            font-weight: 700;
            text-decoration: none;

            white-space: nowrap;

            transition: 0.2s;
        }

        .create-competition-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232, 111, 45, 0.2);
        }


        /* =========================
           COMPETITION LIST
        ========================= */

        .competition-list {
            display: grid;
            gap: 18px;
        }

        .competition-card {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 30px;

            padding: 26px 28px;

            border: 1px solid #e4e4df;
            border-radius: 18px;

            background: white;

            transition: 0.2s;
        }

        .competition-card:hover {
            transform: translateY(-3px);

            border-color: #ddd;

            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        }

        .competition-card-content h2 {
            margin: 0 0 8px;

            color: #222;

            font-size: 23px;
        }

        .competition-description {
            max-width: 700px;

            margin: 0 0 20px;

            color: #777;

            line-height: 1.6;
        }


        /* =========================
           META INFO
        ========================= */

        .competition-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .competition-meta-item {
            display: flex;
            align-items: center;
            gap: 7px;

            padding: 8px 12px;

            border-radius: 9px;

            background: #f7f7f4;

            color: #555;

            font-size: 14px;
        }

        .competition-meta-item strong {
            color: #222;
        }


        /* =========================
           VIEW BUTTON
        ========================= */

        .competition-card-action {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-competition-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 44px;
            padding: 0 18px;

            border: 1px solid #ddd;
            border-radius: 10px;

            background: white;
            color: #333;

            font-size: 14px;
            font-weight: 700;
            text-decoration: none;

            white-space: nowrap;

            transition: 0.2s;
        }

        .view-competition-button:hover {
            border-color: #e86f2d;
            background: #fff8f4;
            color: #e86f2d;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty-competitions {
            padding: 60px 30px;

            border: 1px dashed #d7d7d2;
            border-radius: 18px;

            background: white;

            text-align: center;
        }

        .empty-icon {
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

        .empty-competitions h2 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .empty-competitions p {
            margin: 0;
            color: #777;
        }


        /* =========================
           FOOTER
        ========================= */

        .competitions-footer {
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

            .competitions-nav {
                flex-wrap: wrap;
            }

            .competitions-nav-links {
                order: 3;

                width: 100%;

                justify-content: center;
            }

            .competitions-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .competition-card {
                grid-template-columns: 1fr;
            }

            .competition-card-action {
                justify-content: flex-start;
            }
        }


        @media (max-width: 600px) {

            .competitions-nav {
                padding: 18px 20px;
            }

            .competitions-page {
                padding: 45px 20px 60px;
            }

            .competitions-nav-links {
                gap: 15px;
            }

            .competitions-nav-auth span {
                display: none;
            }

            .competitions-header h1 {
                font-size: 38px;
                letter-spacing: -1px;
            }

            .create-competition-button {
                width: 100%;
            }

            .competition-card {
                padding: 22px 20px;
            }

            .competition-meta {
                flex-direction: column;
            }

            .competition-meta-item {
                width: 100%;
            }

            .view-competition-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<nav class="competitions-nav">

    <a href="/" class="competitions-logo">
        Discapp
    </a>


    <div class="competitions-nav-links">

        <a href="/">
            Sākums
        </a>

        <a
            href="/competitions"
            class="active"
        >
            Sacensības
        </a>

        <a href="/players">
            Spēlētāji
        </a>

        @auth
            <a href="/profile">
                Mans profils
            </a>
        @endauth

    </div>


    <div class="competitions-nav-auth">

        @auth

            <span>
                Sveiks,
                {{ auth()->user()->name }}
                {{ auth()->user()->surname }}!
            </span>

            <form
                method="POST"
                action="/logout"
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
                href="/login"
                class="nav-login"
            >
                Pieslēgties
            </a>

            <a
                href="/register"
                class="nav-register"
            >
                Reģistrēties
            </a>

        @endauth

    </div>

</nav>


<main class="competitions-page">

    <section class="competitions-header">

        <div class="competitions-header-text">

            <span class="page-badge">
                SACENSĪBAS
            </span>

            <h1>
                Disku golfa sacensības
            </h1>

            <p>
                Apskati pieejamās sacensības,
                pievienojies spēlei un seko līdzi
                rezultātiem.
            </p>

        </div>


        @auth

            <a
                class="create-competition-button"
                href="/competitions/create"
            >
                + Izveidot sacensības
            </a>

        @endauth

    </section>


    <section class="competition-list">

        @forelse($competitions as $competition)

            <article class="competition-card">

                <div class="competition-card-content">

                    <h2>
                        {{ $competition->name }}
                    </h2>


                    @if($competition->description)

                        <p class="competition-description">
                            {{ $competition->description }}
                        </p>

                    @endif


                    <div class="competition-meta">

                        <div class="competition-meta-item">

                            <span>📅</span>

                            <span>
                                <strong>Datums:</strong>
                                {{ $competition->date }}
                            </span>

                        </div>


                        <div class="competition-meta-item">

                            <span>📍</span>

                            <span>
                                <strong>Vieta:</strong>
                                {{ $competition->location }}
                            </span>

                        </div>

                    </div>

                </div>


                <div class="competition-card-action">

                    <a
                        href="/competitions/{{ $competition->id }}"
                        class="view-competition-button"
                    >
                        Skatīt sacensības →
                    </a>

                </div>

            </article>

        @empty

            <div class="empty-competitions">

                <div class="empty-icon">
                    🥏
                </div>

                <h2>
                    Sacensību pašlaik nav
                </h2>

                <p>
                    Kad tiks izveidotas jaunas sacensības,
                    tās parādīsies šeit.
                </p>

            </div>

        @endforelse

    </section>


    <footer class="competitions-footer">
        Discapp — disku golfa sacensību un rezultātu pārvaldības sistēma.
    </footer>

</main>

</body>
</html>