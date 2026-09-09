<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <nav>
        <h2>DiscGolf</h2>

        <div>
            <a href="/">Sākums</a>
            <a href="/competitions">Sacensības</a>

            @if (Auth::check())
                <a href="/profile">Mans profils</a>
            @endif
        </div>

        <div class="nav-auth">
            @if (Auth::check())
                <span>Sveiks, {{ Auth::user()->name }}!</span>

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit">Iziet</button>
                </form>
            @else
                <a href="/login">Pieslēgties</a>
                <a href="/register">Reģistrēties</a>
            @endif
        </div>
    </nav>

    <main>
        <h1>DiscGolf</h1>

        <p>Disku golfa sacensību un rezultātu pārvaldības sistēma.</p>

        <a class="button" href="/competitions">
            Apskatīt sacensības
        </a>

        @if (Auth::check())
            <a class="button" href="/profile">
                Mans profils
            </a>
        @endif
    </main>

</body>
</html>