<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mans profils - DiscGolf</title>

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

    <div class="nav-auth">
        <span>Sveiks, {{ $user->name }}!</span>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Iziet</button>
        </form>
    </div>
</nav>

<main>

    <h1>Mans profils</h1>

    <div class="profile">

        <h2>{{ $user->name }}</h2>

        <p>
            <strong>E-pasts:</strong>
            {{ $user->email }}
        </p>

        <p>
            <strong>Loma:</strong>
            {{ $user->role }}
        </p>

    </div>

    <div class="profile-links">

        <a href="{{ route('profile.discs.index') }}" class="button">
            Mani diski
        </a>

    </div>

</main>

</body>
</html>