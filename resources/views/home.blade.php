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
            <a href="#">Sacensības</a>
            <a href="#">Spēlētāji</a>
            @if (Auth::check())
    <span>Sveiks, {{ Auth::user()->name }}!</span>

    <form method="POST" action="/logout" style="display:inline;">
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

        <a class="button" href="#">Apskatīt sacensības</a>
    </main>

</body>
</html>