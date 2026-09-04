<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Reģistrācija - DiscGolf</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<main>
    <h1>Reģistrācija</h1>

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Vārds" required>

        <input type="email" name="email" placeholder="E-pasts" required>

        <input type="password" name="password" placeholder="Parole" required>

        <input type="password" name="password_confirmation"
               placeholder="Atkārto paroli" required>

        <button type="submit">Reģistrēties</button>
    </form>

    <p>
        Jau ir konts?
        <a href="/login">Pieslēgties</a>
    </p>
</main>

</body>
</html>