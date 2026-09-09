<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pievienot disku - DiscGolf</title>

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

    <h1>Pievienot disku</h1>

    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

        </div>

    @endif

    <form method="POST" action="{{ route('profile.discs.store') }}">

        @csrf

        <label for="name">
            Diska nosaukums
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Piemēram, Destroyer"
            required
        >

        <label for="type">
            Diska tips
        </label>

        <select id="type" name="type" required>

            <option value="">Izvēlies tipu</option>
            <option value="Distance Driver">Distance Driver</option>
            <option value="Fairway Driver">Fairway Driver</option>
            <option value="Midrange">Midrange</option>
            <option value="Putter">Putter</option>

        </select>

        <button type="submit" class="button">
            Pievienot disku
        </button>

    </form>

    <br>

    <a href="{{ route('profile.discs.index') }}" class="button">
        Atpakaļ
    </a>

</main>

</body>
</html>