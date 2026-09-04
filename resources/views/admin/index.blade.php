<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Admin panelis</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>
    <h2>DiscGolf Admin</h2>

    <a href="/">Uz sākumu</a>
</nav>

<main>
    <h1>Admin panelis</h1>

    <p>Sveiks, {{ auth()->user()->name }}!</p>

    <p>Šeit vēlāk pārvaldīsim sacensības, laukumus un lietotājus.</p>
</main>

</body>
</html>