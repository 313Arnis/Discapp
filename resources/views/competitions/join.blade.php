<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pievienoties sacensībām - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>
    <h2>DiscGolf</h2>

    <div>
        <a href="/">Sākums</a>
        <a href="/competitions">Sacensības</a>
        <a href="/profile">Mans profils</a>
    </div>
</nav>


<main>

    <div class="join-box">

        <h1>Pievienoties sacensībām</h1>

        <h2>{{ $competition->name }}</h2>

        <p>
            Tavs reitings:
            <strong>{{ $rating }}</strong>
        </p>

        <form method="POST" action="{{ route('competitions.join.store', $competition) }}">

            @csrf

            <label for="division">
                Izvēlies divīziju
            </label>

            <select id="division" name="division" required>

                <option value="">
                    Izvēlies divīziju
                </option>

                @foreach($divisions as $code => $name)

                    <option value="{{ $code }}">
                        {{ $name }}
                    </option>

                @endforeach

            </select>

            @error('division')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" class="button">
                Pieteikties
            </button>

            <a href="{{ route('competitions.show', $competition) }}" class="button">
                Atcelt
            </a>

        </form>

    </div>

</main>

</body>
</html>