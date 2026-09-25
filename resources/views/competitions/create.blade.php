<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Izveidot sacensības - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>
    <h2>
        <a href="/">DiscGolf</a>
    </h2>

    <div>
        <a href="/">Sākums</a>
        <a href="{{ route('competitions.index') }}">Sacensības</a>
        <a href="{{ route('profile') }}">Mans profils</a>
    </div>
</nav>

<main>

    <div class="card">

        <h1>Izveidot sacensības</h1>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('competitions.store') }}"
        >
            @csrf

            <div class="form-group">
                <label for="name">Sacensību nosaukums</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description">Apraksts</label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="date">Sacensību datums</label>

                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ old('date') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="course_id">Trase</label>

                <select id="course_id" name="course_id" required>
                    <option value="">Izvēlies trasi</option>

                    @foreach($courses as $course)
                        <option
                            value="{{ $course->id }}"
                            @selected(old('course_id') == $course->id)
                        >
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="max_players">
                    Maksimālais dalībnieku skaits
                </label>

                <input
                    type="number"
                    id="max_players"
                    name="max_players"
                    min="1"
                    value="{{ old('max_players') }}"
                >
            </div>

            <div class="form-group">
                <label for="status">Sacensību statuss</label>

                <select id="status" name="status" required>
                    <option
                        value="planned"
                        @selected(old('status', 'planned') === 'planned')
                    >
                        Plānotas
                    </option>

                    <option
                        value="active"
                        @selected(old('status') === 'active')
                    >
                        Notiek
                    </option>

                    <option
                        value="finished"
                        @selected(old('status') === 'finished')
                    >
                        Pabeigtas
                    </option>

                    <option
                        value="cancelled"
                        @selected(old('status') === 'cancelled')
                    >
                        Atceltas
                    </option>
                </select>
            </div>

            <div class="registration-section">

                <h2>Reģistrācijas periods</h2>

                <p>
                    Norādi, no kura datuma un laika spēlētāji
                    varēs pieteikties sacensībām.
                </p>

                <div class="registration-fields">

                    <div class="form-group">
                        <label for="registration_starts_at">
                            Reģistrācijas sākums
                        </label>

                        <input
                            type="datetime-local"
                            id="registration_starts_at"
                            name="registration_starts_at"
                            value="{{ old('registration_starts_at') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="registration_ends_at">
                            Reģistrācijas beigas
                        </label>

                        <input
                            type="datetime-local"
                            id="registration_ends_at"
                            name="registration_ends_at"
                            value="{{ old('registration_ends_at') }}"
                            required
                        >
                    </div>

                </div>

            </div>

            <div class="form-actions">

                <button type="submit" class="button">
                    Izveidot sacensības
                </button>

                <a
                    href="{{ route('competitions.index') }}"
                    class="secondary-button"
                >
                    Atpakaļ
                </a>

            </div>

        </form>

    </div>

</main>

<style>
    .registration-section {
        margin: 28px 0;
        padding: 22px;
        border: 1px solid #dce5dd;
        border-radius: 8px;
        background: #f7faf7;
    }

    .registration-section h2 {
        margin: 0 0 8px;
        font-size: 20px;
    }

    .registration-section p {
        margin: 0 0 20px;
        color: #666;
    }

    .registration-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .registration-fields input {
        width: 100%;
        box-sizing: border-box;
    }

    @media (max-width: 600px) {
        .registration-fields {
            grid-template-columns: 1fr;
        }
    }
</style>

</body>
</html>