<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rediģēt sacensības - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>

    <h2>
        <a href="/">
            DiscGolf
        </a>
    </h2>

    <div>
        <a href="/">Sākums</a>
        <a href="{{ route('competitions.index') }}">Sacensības</a>
        <a href="{{ route('profile') }}">Mans profils</a>
    </div>

    @auth

        <div class="nav-auth">

            <span>
                Sveiks, {{ auth()->user()->name }}!
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    Iziet
                </button>
            </form>

        </div>

    @endauth

</nav>


<main>

    <h1>Rediģēt sacensības</h1>


    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <p>
                    {{ $error }}
                </p>

            @endforeach

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('competitions.update', $competition) }}"
    >

        @csrf
        @method('PUT')


        {{-- SACENSĪBU NOSAUKUMS --}}

        <label for="name">
            Sacensību nosaukums
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $competition->name) }}"
            required
        >


        {{-- APRAKSTS --}}

        <label for="description">
            Apraksts
        </label>

        <textarea
            id="description"
            name="description"
        >{{ old('description', $competition->description) }}</textarea>


        {{-- DATUMS --}}

        <label for="date">
            Datums
        </label>

        <input
            type="date"
            id="date"
            name="date"
            value="{{ old(
                'date',
                $competition->date?->format('Y-m-d')
            ) }}"
            required
        >


        {{-- TRASE --}}

        <label for="course_id">
            Trase
        </label>

        <select
            id="course_id"
            name="course_id"
            required
        >

            <option value="">
                -- Izvēlies trasi --
            </option>

            @foreach($courses as $course)

                <option
                    value="{{ $course->id }}"
                    {{ old(
                        'course_id',
                        $competition->course_id
                    ) == $course->id ? 'selected' : '' }}
                >
                    {{ $course->name }}
                </option>

            @endforeach

        </select>


        {{-- MAKSIMĀLAIS SPĒLĒTĀJU SKAITS --}}

        <label for="max_players">
            Maksimālais spēlētāju skaits
        </label>

        <input
            type="number"
            id="max_players"
            name="max_players"
            value="{{ old(
                'max_players',
                $competition->max_players
            ) }}"
            min="1"
        >


        {{-- STATUSS --}}

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
                {{ old(
                    'status',
                    $competition->status
                ) === 'planned' ? 'selected' : '' }}
            >
                Plānotas
            </option>

            <option
                value="active"
                {{ old(
                    'status',
                    $competition->status
                ) === 'active' ? 'selected' : '' }}
            >
                Aktīvas
            </option>

            <option
                value="finished"
                {{ old(
                    'status',
                    $competition->status
                ) === 'finished' ? 'selected' : '' }}
            >
                Pabeigtas
            </option>

            <option
                value="cancelled"
                {{ old(
                    'status',
                    $competition->status
                ) === 'cancelled' ? 'selected' : '' }}
            >
                Atceltas
            </option>

        </select>


        <button type="submit">
            Saglabāt izmaiņas
        </button>

    </form>


    <a
        href="{{ route('competitions.show', $competition) }}"
        class="secondary-button"
    >
        Atcelt
    </a>


</main>

</body>
</html>