<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Pieslēgšanās - DiscGolf</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<main>
    <h1>Pieslēgties</h1>

    @if ($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/login">
        
        @csrf

        <input type="email" name="email" placeholder="E-pasts" required>

        <input type="password" name="password" placeholder="Parole" required>

        <button type="submit">Pieslēgties</button>
    </form>

    <p>
        Nav konta?
        <a href="/register">Reģistrēties</a>
    </p>
</main>

</body>
</html>