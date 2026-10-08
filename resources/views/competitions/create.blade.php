
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izveidot sacensības - Discapp</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* Discapp sākumlapas vizuālais stils */
        body {
            background: #f8f8f6;
            color: #222;
            font-family: Arial, sans-serif;
        }

        /* Navigācija */
        body nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin: 0 12px;
            padding: 20px 36px;
            background: #f8f8f6;
            color: #222;
            box-shadow: 0 3px 9px rgba(0,0,0,.08);
        }

        body nav h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }

        body nav h2 a {
            color: #ed6b26;
            text-decoration: none;
        }

        body nav a {
            color: #222;
            text-decoration: none;
            font-size: 17px;
            font-weight: 700;
        }

        body nav a:hover {
            color: #ed6b26;
            text-decoration: none;
        }

        .discapp-nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 32px;
        }

        .discapp-nav-auth {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .discapp-nav-auth span {
            color: #666;
            white-space: nowrap;
        }

        .discapp-nav-auth form {
            margin: 0;
            padding: 0;
            width: auto;
            max-width: none;
            border: 0;
            background: transparent;
        }

        body nav .discapp-logout {
            background: #ed6b26;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px 22px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        body nav .discapp-logout:hover {
            background: #d95b19;
        }

        /* Galvenais saturs */
        main.discapp-create-page {
            max-width: 1000px;
            margin: 55px auto 90px;
            padding: 0 20px;
        }

        .discapp-create-heading {
            margin-bottom: 30px;
        }

        .discapp-eyebrow {
            display: inline-block;
            padding: 9px 17px;
            margin-bottom: 18px;
            background: #fff0e8;
            color: #dc5a15;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .7px;
        }

        .discapp-create-heading h1 {
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 800;
            letter-spacing: -1.5px;
            margin: 0 0 12px;
        }

        .discapp-create-heading h1 span {
            color: #ed6b26;
        }

        .discapp-create-heading p {
            font-size: 17px;
            color: #777;
            margin: 0;
            line-height: 1.7;
        }

        /* Balta kartīte */
        .discapp-create-card {
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 16px 45px rgba(0,0,0,.045);
        }

        /* Noņem vecā CSS formas ierobežojumus */
        main form.discapp-create-form:not(.player-search) {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            border-radius: 0;
        }

        .discapp-form-section {
            padding: 35px 42px;
            border-bottom: 1px solid #ededed;
        }

        .discapp-section-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 26px;
        }

        .discapp-section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            background: #fff0e8;
            color: #ed6b26;
            font-weight: 800;
        }

        .discapp-section-heading h2 {
            margin: 0 0 4px;
            font-size: 21px;
            text-align: left;
        }

        .discapp-section-heading p {
            margin: 0;
            color: #888;
            font-size: 13px;
        }

        .discapp-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 23px;
        }

        .discapp-form-group {
            min-width: 0;
        }

        .discapp-form-group.full-width {
            grid-column: 1 / -1;
        }

        main .discapp-create-form .discapp-form-group label {
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        main .discapp-create-form .discapp-form-group input,
        main .discapp-create-form .discapp-form-group select,
        main .discapp-create-form .discapp-form-group textarea {
            display: block;
            width: 100%;
            min-height: 50px;
            padding: 13px 15px;
            margin: 0;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 11px;
            color: #222;
            font-size: 15px;
            transition: .2s;
        }

        main .discapp-create-form .discapp-form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        main .discapp-create-form .discapp-form-group input:focus,
        main .discapp-create-form .discapp-form-group select:focus,
        main .discapp-create-form .discapp-form-group textarea:focus {
            background: white;
            border-color: #ed6b26;
            outline: none;
            box-shadow: 0 0 0 3px rgba(237,107,38,.12);
        }

        .discapp-required {
            color: #ed6b26;
        }

        .discapp-field-hint {
            margin: 8px 0 0;
            font-size: 12px;
            color: #888;
        }

        .discapp-registration-note {
            margin-top: 25px;
            padding: 17px 20px;
            border-radius: 12px;
            background: #fff6f0;
            color: #89512f;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Pogas */
        .discapp-form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 14px;
            padding: 28px 42px;
            background: #fcfcfc;
        }

        main .discapp-create-form .discapp-submit-button {
            width: auto;
            padding: 15px 27px;
            border: none;
            border-radius: 12px;
            background: #ed6b26;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        main .discapp-create-form .discapp-submit-button:hover {
            background: #d95b19;
        }

        .discapp-back-button {
            display: inline-block;
            padding: 14px 25px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            color: #222;
            font-weight: 700;
            text-decoration: none;
        }

        .discapp-back-button:hover {
            background: #f4f4f4;
            text-decoration: none;
        }

        /* Kļūdu paziņojumi */
        .discapp-errors {
            padding: 18px 22px;
            margin-bottom: 24px;
            border-radius: 12px;
            border: 1px solid #f1b9b9;
            background: #fff1f1;
            color: #a33333;
        }

        .discapp-errors ul {
            display: block;
            max-width: none;
            list-style: disc;
            padding-left: 22px;
            margin: 10px 0 0;
        }

        .discapp-errors li {
            display: list-item;
            padding: 0;
            margin: 4px 0;
            background: transparent;
            border: none;
        }

        @media (max-width: 900px) {
            body nav {
                flex-wrap: wrap;
                padding: 20px;
            }

            .discapp-nav-links {
                order: 3;
                width: 100%;
                gap: 18px;
                justify-content: flex-start;
            }
        }

        @media (max-width: 650px) {
            main.discapp-create-page {
                margin-top: 32px;
            }

            .discapp-form-section {
                padding: 26px 20px;
            }

            .discapp-form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .discapp-form-actions {
                flex-direction: column-reverse;
                padding: 22px 20px;
            }

            main .discapp-create-form .discapp-submit-button,
            .discapp-back-button {
                width: 100%;
                text-align: center;
            }

            .discapp-nav-auth {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

<nav>
    <h2>
        <a href="{{ route('home') }}">Discapp</a>
    </h2>

    <div class="discapp-nav-links">
        <a href="{{ route('home') }}">Sākums</a>
        <a href="{{ route('competitions.index') }}">Sacensības</a>
        <a href="{{ route('players.index') }}">Spēlētāji</a>

        @auth
            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('practice.index') }}">Practice</a>
                <a href="{{ route('profile') }}">Mans profils</a>
            @endif
        @endauth
    </div>

    <div class="discapp-nav-auth">
        @auth
            <span>Sveiks, {{ auth()->user()->name }}!</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="discapp-logout">
                    Iziet
                </button>
            </form>
        @endauth
    </div>
</nav>

<main class="discapp-create-page">

    <div class="discapp-create-heading">
        <span class="discapp-eyebrow">DISKU GOLFA SACENSĪBAS</span>

        <h1>Izveido <span>sacensības.</span></h1>

        <p>
            Izveido jaunas disku golfa sacensības, izvēlies trasi
            un nosaki spēlētāju reģistrācijas periodu.
        </p>
    </div>

    @if($errors->any())
        <div class="discapp-errors" role="alert">
            <strong>Lūdzu, izlabo šīs kļūdas:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="discapp-create-card">

        <form
            method="POST"
            action="{{ route('competitions.store') }}"
            class="discapp-create-form"
        >
            @csrf

            <!-- 1. PAMATINFORMĀCIJA -->
            <section class="discapp-form-section">

                <div class="discapp-section-heading">
                    <span class="discapp-section-number">01</span>
                    <div>
                        <h2>Pamatinformācija</h2>
                        <p>Galvenā informācija par sacensībām</p>
                    </div>
                </div>

                <div class="discapp-form-grid">

                    <div class="discapp-form-group full-width">
                        <label for="name">
                            Sacensību nosaukums
                            <span class="discapp-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Piemēram, Rudens disku golfa kauss"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="discapp-form-group full-width">
                        <label for="description">Apraksts</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Sacensību apraksts..."
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="discapp-form-group">
                        <label for="date">
                            Sacensību datums
                            <span class="discapp-required">*</span>
                        </label>
                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ old('date') }}"
                            required
                        >
                    </div>

                    <div class="discapp-form-group">
                        <label for="course_id">
                            Trase
                            <span class="discapp-required">*</span>
                        </label>
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

                </div>
            </section>

            <!-- 2. DALĪBNIEKI -->
            <section class="discapp-form-section">

                <div class="discapp-section-heading">
                    <span class="discapp-section-number">02</span>
                    <div>
                        <h2>Dalībnieki un statuss</h2>
                        <p>Sacensību ierobežojumi un statuss</p>
                    </div>
                </div>

                <div class="discapp-form-grid">

                    <div class="discapp-form-group">
                        <label for="max_players">
                            Maksimālais dalībnieku skaits
                        </label>
                        <input
                            type="number"
                            id="max_players"
                            name="max_players"
                            min="1"
                            value="{{ old('max_players') }}"
                            placeholder="Piemēram, 72"
                        >
                        <p class="discapp-field-hint">
                            Atstāj tukšu, ja dalībnieku skaits nav ierobežots.
                        </p>
                    </div>

                    <div class="discapp-form-group">
                        <label for="status">
                            Sacensību statuss
                            <span class="discapp-required">*</span>
                        </label>
                        <select id="status" name="status" required>
                            <option value="planned"
                                @selected(old('status', 'planned') === 'planned')>
                                Plānotas
                            </option>
                            <option value="active"
                                @selected(old('status') === 'active')>
                                Notiek
                            </option>
                            <option value="finished"
                                @selected(old('status') === 'finished')>
                                Pabeigtas
                            </option>
                            <option value="cancelled"
                                @selected(old('status') === 'cancelled')>
                                Atceltas
                            </option>
                        </select>
                    </div>

                </div>
            </section>

            <!-- 3. REĢISTRĀCIJA -->
            <section class="discapp-form-section">

                <div class="discapp-section-heading">
                    <span class="discapp-section-number">03</span>
                    <div>
                        <h2>Reģistrācijas periods</h2>
                        <p>Laiks, kad spēlētāji varēs pieteikties</p>
                    </div>
                </div>

                <div class="discapp-form-grid">

                    <div class="discapp-form-group">
                        <label for="registration_starts_at">
                            Reģistrācijas sākums
                            <span class="discapp-required">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            id="registration_starts_at"
                            name="registration_starts_at"
                            value="{{ old('registration_starts_at') }}"
                            required
                        >
                    </div>

                    <div class="discapp-form-group">
                        <label for="registration_ends_at">
                            Reģistrācijas beigas
                            <span class="discapp-required">*</span>
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

                <div class="discapp-registration-note">
                    <strong>Informācija:</strong>
                    Reģistrācijas sākumam jābūt agrākam par beigām.
                    Pieteikšanās perioda ievērošana tiek kontrolēta servera pusē,
                    kad attiecīgās pārbaudes ir ieviestas.
                </div>

            </section>

            <!-- POGAS -->
            <div class="discapp-form-actions">
                <a
                    href="{{ route('competitions.index') }}"
                    class="discapp-back-button"
                >
                    Atpakaļ
                </a>

                <button type="submit" class="discapp-submit-button">
                    Izveidot sacensības →
                </button>
            </div>

        </form>
    </div>

</main>

</body>
</html>
