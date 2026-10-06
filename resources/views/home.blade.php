<!DOCTYPE html>
<html lang="lv">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Discapp</title>

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

        .home-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;

            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;

            background: transparent;
        }

        .home-logo {
            font-size: 24px;
            font-weight: 800;
            color: #e86f2d;
            text-decoration: none;
        }

        .home-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .home-nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .home-nav-links a:hover {
            color: #e86f2d;
        }

        .home-nav-auth {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .home-nav-auth span {
            color: #666;
            font-size: 14px;
        }

        .home-nav-auth form {
            margin: 0;
        }

        .home-login {
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        .home-register,
        .home-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 10px;

            background: #e86f2d;
            color: white;

            padding: 10px 18px;

            font-weight: 700;
            text-decoration: none;
            cursor: pointer;

            transition: 0.2s;
        }

        .home-register:hover,
        .home-logout:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }


        /* =========================
           HERO
        ========================= */

        .home-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 70px 30px 60px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 70px;

            min-height: 500px;
        }

        .hero-badge {
            display: inline-block;

            margin-bottom: 18px;
            padding: 7px 13px;

            border-radius: 50px;

            background: #fff0e7;
            color: #d95f1f;

            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .hero h1 {
            max-width: 700px;
            margin: 0 0 20px;

            font-size: clamp(48px, 7vw, 78px);
            line-height: 0.98;
            letter-spacing: -3px;
        }

        .hero h1 span {
            color: #e86f2d;
        }

        .hero-description {
            max-width: 600px;

            margin: 0 0 32px;

            color: #666;

            font-size: 18px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-primary,
        .hero-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 48px;
            padding: 0 22px;

            border-radius: 10px;

            font-weight: 700;
            text-decoration: none;

            transition: 0.2s;
        }

        .hero-primary {
            background: #e86f2d;
            color: white;
        }

        .hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232, 111, 45, 0.2);
        }

        .hero-secondary {
            border: 1px solid #ddd;
            background: white;
            color: #333;
        }

        .hero-secondary:hover {
            border-color: #e86f2d;
            color: #e86f2d;
        }


        /* =========================
           HERO CARD
        ========================= */

        .hero-card {
            position: relative;

            padding: 35px;

            border: 1px solid #e4e4df;
            border-radius: 24px;

            background: white;

            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.06);
        }

        .hero-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 65px;
            height: 65px;

            margin-bottom: 25px;

            border-radius: 18px;

            background: #fff0e7;

            font-size: 30px;
        }

        .hero-card h2 {
            margin: 0 0 10px;
            font-size: 24px;
        }

        .hero-card > p {
            margin: 0 0 25px;
            color: #777;
            line-height: 1.6;
        }

        .hero-stat {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 16px 0;

            border-top: 1px solid #eee;
        }

        .hero-stat span {
            color: #777;
        }

        .hero-stat strong {
            color: #222;
        }

        .hero-stat strong.orange {
            color: #e86f2d;
        }


        /* =========================
           FEATURES
        ========================= */

        .features {
            margin-top: 90px;
        }

        .features-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .features-header h2 {
            margin: 0 0 10px;
            font-size: 34px;
        }

        .features-header p {
            margin: 0;
            color: #777;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .feature-card {
            padding: 28px;

            border: 1px solid #e4e4df;
            border-radius: 18px;

            background: white;

            transition: 0.2s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        }

        .feature-card.practice-card {
            border-color: rgba(232, 111, 45, 0.35);
            background: linear-gradient(
                145deg,
                #ffffff,
                #fffaf6
            );
        }

        .feature-number {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 42px;
            height: 42px;

            margin-bottom: 20px;

            border-radius: 12px;

            background: #fff0e7;
            color: #e86f2d;

            font-weight: 800;
        }

        .feature-card h3 {
            margin: 0 0 10px;
            font-size: 19px;
        }

        .feature-card p {
            margin: 0;

            color: #777;

            line-height: 1.6;
            font-size: 14px;
        }

        .feature-action {
            display: inline-flex;
            align-items: center;

            margin-top: 18px;

            color: #e86f2d;

            font-size: 14px;
            font-weight: 800;
            text-decoration: none;

            transition: 0.2s;
        }

        .feature-action:hover {
            gap: 5px;
        }


        /* =========================
           FOOTER
        ========================= */

        .home-footer {
            margin-top: 90px;
            padding-top: 25px;

            border-top: 1px solid #ddd;

            color: #999;
            font-size: 13px;
            text-align: center;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1050px) {

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 900px) {

            .home-nav {
                flex-wrap: wrap;
            }

            .home-nav-links {
                order: 3;
                width: 100%;
                justify-content: center;
            }

            .hero {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero h1 {
                letter-spacing: -2px;
            }

        }

        @media (max-width: 600px) {

            .home-page {
                padding: 45px 20px;
            }

            .home-nav {
                padding: 18px 20px;
            }

            .home-nav-auth span {
                display: none;
            }

            .home-nav-links {
                gap: 15px;
                flex-wrap: wrap;
            }

            .hero {
                min-height: auto;
            }

            .hero h1 {
                font-size: 48px;
            }

            .hero-description {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-primary,
            .hero-secondary {
                width: 100%;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>


    <nav class="home-nav">

        <a href="/" class="home-logo">
            Discapp
        </a>


        <div class="home-nav-links">

            <a href="/">
                Sākums
            </a>

            <a href="/competitions">
                Sacensības
            </a>

            <a href="/players">
                Spēlētāji
            </a>


            @if (Auth::check())

                <a href="{{ route('practice.index') }}">
                    Practice
                </a>

                <a href="/profile">
                    Mans profils
                </a>

            @endif

        </div>


        <div class="home-nav-auth">

            @if (Auth::check())

                <span>
                    Sveiks,
                    {{ Auth::user()->name }}
                    {{ Auth::user()->surname }}!
                </span>


                <form method="POST" action="/logout">

                    @csrf

                    <button
                        type="submit"
                        class="home-logout"
                    >
                        Iziet
                    </button>

                </form>

            @else

                <a
                    href="/login"
                    class="home-login"
                >
                    Pieslēgties
                </a>

                <a
                    href="/register"
                    class="home-register"
                >
                    Reģistrēties
                </a>

            @endif

        </div>

    </nav>



    <main class="home-page">


        <section class="hero">


            <div class="hero-content">

                <span class="hero-badge">
                    DISKU GOLFS VIENUVIET
                </span>


                <h1>
                    Spēlē. Sacensties.
                    <span>Uzvari.</span>
                </h1>


                <p class="hero-description">
                    Discapp palīdz pārvaldīt disku golfa
                    sacensības, sekot līdzi rezultātiem,
                    spēlētāju reitingiem, Practice apļiem
                    un savai disku kolekcijai.
                </p>


                <div class="hero-buttons">

                    <a
                        class="hero-primary"
                        href="/competitions"
                    >
                        Apskatīt sacensības →
                    </a>


                    @if (Auth::check())

                        <a
                            class="hero-secondary"
                            href="{{ route('practice.index') }}"
                        >
                            Sākt Practice apli
                        </a>

                    @else

                        <a
                            class="hero-secondary"
                            href="/register"
                        >
                            Izveidot kontu
                        </a>

                    @endif

                </div>

            </div>



            <div class="hero-card">

                <div class="hero-card-icon">
                    🥏
                </div>


                <h2>
                    Discapp
                </h2>


                <p>
                    Viss nepieciešamais disku golfa
                    spēlētājam vienuviet.
                </p>


                <div class="hero-stat">

                    <span>
                        Sacensības
                    </span>

                    <strong class="orange">
                        Spēlē
                    </strong>

                </div>


                <div class="hero-stat">

                    <span>
                        Practice
                    </span>

                    <strong>
                        Trenējies
                    </strong>

                </div>


                <div class="hero-stat">

                    <span>
                        Rezultāti
                    </span>

                    <strong>
                        Seko līdzi
                    </strong>

                </div>


                <div class="hero-stat">

                    <span>
                        Reitings
                    </span>

                    <strong>
                        Attīsties
                    </strong>

                </div>


                <div class="hero-stat">

                    <span>
                        Disku kolekcija
                    </span>

                    <strong>
                        Pārvaldi
                    </strong>

                </div>

            </div>

        </section>



        <section class="features">


            <div class="features-header">

                <h2>
                    Viss nepieciešamais spēlei
                </h2>

                <p>
                    Vienkārši rīki spēlētājiem un
                    sacensību organizatoriem.
                </p>

            </div>



            <div class="feature-grid">


                <div class="feature-card">

                    <div class="feature-number">
                        01
                    </div>

                    <h3>
                        Sacensības
                    </h3>

                    <p>
                        Apskati gaidāmās sacensības,
                        pievienojies tām un ievadi
                        savus rezultātus.
                    </p>

                </div>



                <div class="feature-card practice-card">

                    <div class="feature-number">
                        02
                    </div>

                    <h3>
                        Practice apļi
                    </h3>

                    <p>
                        Izvēlies trasi, izspēlē treniņa
                        apli un saglabā savu rezultātu
                        un rezultātu pret PAR.
                    </p>


                    @if (Auth::check())

                        <a
                            href="{{ route('practice.index') }}"
                            class="feature-action"
                        >
                            Sākt apli →
                        </a>

                    @else

                        <a
                            href="/login"
                            class="feature-action"
                        >
                            Pieslēgties →
                        </a>

                    @endif

                </div>



                <div class="feature-card">

                    <div class="feature-number">
                        03
                    </div>

                    <h3>
                        Rezultāti un reitings
                    </h3>

                    <p>
                        Seko līdzi saviem rezultātiem,
                        vietām sacensībās un spēlētāja
                        reitingam.
                    </p>

                </div>



                <div class="feature-card">

                    <div class="feature-number">
                        04
                    </div>

                    <h3>
                        Disku kolekcija
                    </h3>

                    <p>
                        Saglabā un pārvaldi savus
                        diskus savā personīgajā
                        Discapp profilā.
                    </p>

                </div>

            </div>

        </section>



        <footer class="home-footer">

            Discapp — disku golfa sacensību,
            rezultātu un Practice apļu pārvaldības sistēma.

        </footer>


    </main>


</body>

</html>