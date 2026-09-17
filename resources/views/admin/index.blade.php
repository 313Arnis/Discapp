<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin panelis - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>

    <div>
        <a href="{{ route('home') }}">
            Discapp
        </a>
    </div>

    <div class="nav-auth">

        <a href="{{ route('profile') }}">
            Profils
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Iziet
            </button>
        </form>

    </div>

</nav>


<main class="profile-page">

    <div class="profile-header">

        <div>
            <h1>Admin panelis</h1>

            <p>
                Sveiks, {{ auth()->user()->name }}!
            </p>
        </div>

    </div>


    <!-- STATISTIKA -->

    <section
        class="profile-stats"
        style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        "
    >

        <div>
            <strong>Lietotāji</strong>

            <span>
                {{ $usersCount }}
            </span>
        </div>


        <div>
            <strong>Trases</strong>

            <span>
                {{ $coursesCount }}
            </span>
        </div>


        <div>
            <strong>Sacensības</strong>

            <span>
                {{ $competitionsCount }}
            </span>
        </div>


        <div>
            <strong>Vidējais reitings</strong>

            <span>
                {{ $averageRating }}
            </span>
        </div>

    </section>


    <!-- ĀTRĀS DARBĪBAS -->

    <section class="competitions">

        <h2 style="margin-bottom: 15px;">
            Ātrās darbības
        </h2>


        <div class="competition">

            <div>

                <h2>
                    Lietotāji
                </h2>

                <p>
                    Skati un pārvaldi Discapp lietotājus,
                    viņu lomas un reitingus.
                </p>

            </div>

            <div>

                <a
                    href="{{ route('admin.users') }}"
                    class="btn"
                >
                    Pārvaldīt lietotājus
                </a>

            </div>

        </div>


        <div class="competition">

            <div>

                <h2>
                    Disku golfa trases
                </h2>

                <p>
                    Pievieno un pārvaldi trases,
                    grozus un PAR vērtības.
                </p>

            </div>

            <div>

                <a
                    href="{{ route('courses.index') }}"
                    class="btn"
                >
                    Pārvaldīt trases
                </a>

            </div>

        </div>


        <div class="competition">

            <div>

                <h2>
                    Sacensības
                </h2>

                <p>
                    Sacensību administrēšana būs pieejama
                    nākamajā sistēmas versijā.
                </p>

            </div>

            <div>

                <button
                    class="btn"
                    type="button"
                    disabled
                    style="
                        opacity: 0.5;
                        cursor: not-allowed;
                    "
                >
                    Drīzumā
                </button>

            </div>

        </div>

    </section>


    <!-- PĒDĒJIE LIETOTĀJI -->

    <section
        class="competitions"
        style="margin-top: 30px;"
    >

        <h2 style="margin-bottom: 15px;">
            Jaunākie lietotāji
        </h2>


        @forelse($recentUsers as $user)

            <div class="competition">

                <div>

                    <h2>
                        {{ $user->name }}
                    </h2>

                    <p>
                        {{ $user->email }}
                    </p>

                </div>


                <div style="text-align: right;">

                    <strong>
                        Reitings
                    </strong>

                    <p>
                        {{ $user->rating }}
                    </p>

                </div>

            </div>

        @empty

            <div class="competition">

                <p>
                    Lietotāju vēl nav.
                </p>

            </div>

        @endforelse

    </section>

</main>

</body>
</html>