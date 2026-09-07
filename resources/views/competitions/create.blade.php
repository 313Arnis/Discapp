<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Jaunas sacensības</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<main>

    <h1>Izveidot sacensības</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="/competitions">

        @csrf

        <input type="text" name="name"
               placeholder="Sacensību nosaukums">

        <textarea name="description"
                  placeholder="Apraksts"></textarea>

        <input type="date" name="date">

        <input type="text" name="location"
               placeholder="Norises vieta">

        <input type="number" name="max_players"
               placeholder="Maksimālais spēlētāju skaits">

        <button type="submit">
            Izveidot
        </button>

    </form>

    <br>

    <a href="/competitions">Atpakaļ</a>

</main>

</body>
</html>