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

<form method="POST"
      action="{{ route('profile.discs.store') }}"
      enctype="multipart/form-data">

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

        <option value="Distance Driver">
            Distance Driver
        </option>

        <option value="Fairway Driver">
            Fairway Driver
        </option>

        <option value="Midrange">
            Midrange
        </option>

        <option value="Putter">
            Putter
        </option>

    </select>


    <h2>Diska stats</h2>

    <label for="speed">
        Speed
    </label>

    <input
        type="number"
        id="speed"
        name="speed"
        value="{{ old('speed') }}"
        min="1"
        max="15"
        step="0.5"
        placeholder="Piemēram, 12"
        required
    >


    <label for="glide">
        Glide
    </label>

    <input
        type="number"
        id="glide"
        name="glide"
        value="{{ old('glide') }}"
        min="1"
        max="7"
        step="0.5"
        placeholder="Piemēram, 5"
        required
    >


    <label for="turn">
        Turn
    </label>

    <input
        type="number"
        id="turn"
        name="turn"
        value="{{ old('turn') }}"
        min="-5"
        max="1"
        step="0.5"
        placeholder="Piemēram, -1"
        required
    >


    <label for="fade">
        Fade
    </label>

    <input
        type="number"
        id="fade"
        name="fade"
        value="{{ old('fade') }}"
        min="0"
        max="5"
        step="0.5"
        placeholder="Piemēram, 3"
        required
    >


    <label for="image">
        Diska bilde
    </label>

    <input
        type="file"
        id="image"
        name="image"
        accept="image/jpeg,image/png,image/webp"
    >

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
