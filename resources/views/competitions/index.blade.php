<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Sacensības</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>
    <h2>DiscGolf</h2>
    <div>
        <a href="/">Sākums</a>
        <a href="/competitions">Sacensības</a>
    </div>
</nav>

<main>

    <h1>Disc golfa sacensības</h1>

    @auth
        @if(auth()->user()->role === 'admin')
            <a class="button" href="/competitions/create">
                + Izveidot sacensības
            </a>
        @endif
    @endauth

    @forelse($competitions as $competition)

        <div class="competition">
            <h2>{{ $competition->name }}</h2>

            <p>{{ $competition->description }}</p>

            <p>
                <strong>Datums:</strong>
                {{ $competition->date }}
            </p>

            <p>
                <strong>Vieta:</strong>
                {{ $competition->location }}
            </p>

            <a href="/competitions/{{ $competition->id }}">
                Skatīt sacensības
            </a>
        </div>

    @empty
        <p>Pašlaik nav izveidotu sacensību.</p>
    @endforelse

</main>

</body>
</html>