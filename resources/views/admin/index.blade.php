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

        <form
            method="POST"
            action="{{ route('logout') }}"
        >
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

            <h1>
                Admin panelis
            </h1>

            <p>
                Sveiks, {{ auth()->user()->name }}!
            </p>

        </div>

    </div>


    <section class="profile-stats">

        <div>

            <strong>
                Administrators
            </strong>

            <span>
                {{ auth()->user()->email }}
            </span>

        </div>

        <div>

            <strong>
                Loma
            </strong>

            <span>
                Administrators
            </span>

        </div>

    </section>


    <section class="competitions">

        <div class="competition">

            <div>

                <h2>
                    Disku golfa trases
                </h2>

                <p>
                    Pārvaldi trases, grozus un PAR vērtības.
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
                    Lietotāji
                </h2>

                <a
    href="{{ route('admin.users') }}"
    class="btn"
>
    Pārvaldīt lietotājus
</a>

            </div>

            <div>

                <button
                    class="btn"
                    type="button"
                    disabled
                    style="opacity: 0.5; cursor: not-allowed;"
                >
                    Drīzumā
                </button>

            </div>

        </div>


        <div class="competition">

            <div>

                <h2>
                    Sacensības
                </h2>

                <p>
                    Pārvaldi sistēmā izveidotās sacensības.
                </p>

            </div>

            <div>

                <button
                    class="btn"
                    type="button"
                    disabled
                    style="opacity: 0.5; cursor: not-allowed;"
                >
                    Drīzumā
                </button>

            </div>

        </div>

    </section>

</main>

</body>
</html>