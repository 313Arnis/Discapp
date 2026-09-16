<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jaunas sacensības</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<main>

    <h1>Izveidot sacensības</h1>

    @if ($errors->any())

        @foreach ($errors->all() as $error)

            <p class="error">
                {{ $error }}
            </p>

        @endforeach

    @endif


    <form method="POST" action="{{ route('competitions.store') }}">

        @csrf


        <label for="name">
            Sacensību nosaukums
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Sacensību nosaukums"
            required
        >


        <label for="description">
            Apraksts
        </label>

        <textarea
            id="description"
            name="description"
            placeholder="Apraksts"
        >{{ old('description') }}</textarea>


        <label for="date">
            Datums
        </label>

        <input
            type="date"
            id="date"
            name="date"
            value="{{ old('date') }}"
            required
        >


        <label for="course_id">
            Norises vieta
        </label>

        <select
            id="course_id"
            name="course_id"
            required
        >
            <option value="">
                Izvēlies trasi
            </option>

            @foreach($courses as $course)

                <option
                    value="{{ $course->id }}"
                    {{ old('course_id') == $course->id ? 'selected' : '' }}
                >
                    {{ $course->name }}
                </option>

            @endforeach

        </select>


        <label for="max_players">
            Maksimālais spēlētāju skaits
        </label>

        <input
            type="number"
            id="max_players"
            name="max_players"
            value="{{ old('max_players') }}"
            placeholder="Maksimālais spēlētāju skaits"
            min="1"
        >


        <label for="status">
            Statuss
        </label>

        <select
            id="status"
            name="status"
            required
        >
            <option
                value="planned"
                {{ old('status', 'planned') === 'planned' ? 'selected' : '' }}
            >
                Plānotas
            </option>

            <option
                value="active"
                {{ old('status') === 'active' ? 'selected' : '' }}
            >
                Aktīvas
            </option>

            <option
                value="finished"
                {{ old('status') === 'finished' ? 'selected' : '' }}
            >
                Pabeigtas
            </option>

            <option
                value="cancelled"
                {{ old('status') === 'cancelled' ? 'selected' : '' }}
            >
                Atceltas
            </option>
        </select>


        <button type="submit">
            Izveidot
        </button>

    </form>


    <br>

    <a href="{{ route('competitions.index') }}">
        Atpakaļ
    </a>

</main>

</body>
</html>