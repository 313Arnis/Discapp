<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $competition->name }} - DiscGolf</title>

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

        <h1>{{ $competition->name }}</h1>

        <div class="competition">
            <p>
                <strong>Datums:</strong>
                {{ $competition->date }}
            </p>

            <p>
                <strong>Vieta:</strong>
                {{ $competition->location }}
            </p>

            <p>
                <strong>Apraksts:</strong>
                {{ $competition->description }}
            </p>

            @if($competition->max_players)
                <p>
                    <strong>Maksimālais spēlētāju skaits:</strong>
                    {{ $competition->max_players }}
                </p>
            @endif
        </div>

        <br>

        <a href="/competitions" class="button">
            Atpakaļ uz sacensībām
        </a>
        <form method="POST" action="/competitions/{{ $competition->id }}"
      onsubmit="return confirm('Vai tiešām vēlies dzēst šīs sacensības?');">

    @csrf
    @method('DELETE')

    <button type="submit" class="delete-button">
        Dzēst sacensības
    </button>

</form>
    </main>

</body>
</html>