<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pieslēgšanās - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background: #f7f7f4;
            color: #202020;
        }

        .auth-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;

            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 30px;

            background: transparent;
        }

        .auth-logo {
            color: #e86f2d;
            font-size: 24px;
            font-weight: 800;
            text-decoration: none;
        }

        .auth-back {
            color: #555;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;

            transition: 0.2s;
        }

        .auth-back:hover {
            color: #e86f2d;
        }

        .auth-page {
            display: flex;
            align-items: center;
            justify-content: center;

            min-height: calc(100vh - 85px);

            margin: 0;
            padding: 40px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;

            padding: 38px;

            border: 1px solid #e4e4df;
            border-radius: 20px;

            background: white;

            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.06);
        }

        .auth-badge {
            display: inline-block;

            margin-bottom: 15px;
            padding: 7px 12px;

            border-radius: 50px;

            background: #fff0e7;
            color: #d95f1f;

            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.6px;
        }

        .auth-card h1 {
            margin: 0 0 8px;

            font-size: 34px;
            letter-spacing: -1px;
        }

        .auth-description {
            margin: 0 0 28px;

            color: #777;

            font-size: 14px;
            line-height: 1.6;
        }

        .auth-card .login-form {
            width: 100%;
            max-width: none;

            margin: 0;
            padding: 0;

            border: none;

            background: transparent;

            box-shadow: none;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            color: #444;

            font-size: 13px;
            font-weight: 700;
        }

        .auth-card .login-form input {
            width: 100%;
            height: 48px;

            margin: 0;
            padding: 0 14px;

            border: 1px solid #dcdcd7;
            border-radius: 10px;
            outline: none;

            background: #fff;
            color: #222;

            font-size: 15px;

            transition: 0.2s;
        }

        .auth-card .login-form input:focus {
            border-color: #e86f2d;

            outline: none;

            box-shadow: 0 0 0 3px rgba(232, 111, 45, 0.1);
        }

        .auth-card .login-form button {
            width: 100%;
            height: 48px;

            margin: 6px 0 0;
            padding: 0;

            border: none;
            border-radius: 10px;

            background: #e86f2d;
            color: white;

            font-size: 15px;
            font-weight: 700;

            cursor: pointer;

            transition: 0.2s;
        }

        .auth-card .login-form button:hover {
            background: #d96124;
            transform: translateY(-1px);
        }

        .auth-error {
            margin-bottom: 20px;
            padding: 12px 14px;

            border: 1px solid #f1c5c5;
            border-radius: 10px;

            background: #fff5f5;
            color: #a33;

            font-size: 13px;
        }

        .auth-footer {
            margin: 24px 0 0;

            color: #777;

            font-size: 14px;
            text-align: center;
        }

        .auth-footer a {
            color: #e86f2d;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .auth-nav {
                padding: 18px 20px;
            }

            .auth-page {
                padding: 25px 15px;
            }

            .auth-card {
                padding: 28px 22px;
            }

            .auth-card h1 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

<nav class="auth-nav">

    <a href="/" class="auth-logo">
        Discapp
    </a>

    <a href="/" class="auth-back">
        ← Atpakaļ uz sākumu
    </a>

</nav>


<main class="auth-page">

    <section class="auth-card">

        <span class="auth-badge">
            DISCAPP
        </span>

        <h1>
            Pieslēgties
        </h1>

        <p class="auth-description">
            Pieslēdzies savam Discapp kontam, lai piedalītos
            sacensībās un pārvaldītu savu profilu.
        </p>


        @if ($errors->any())

            <div class="auth-error">
                {{ $errors->first() }}
            </div>

        @endif


        <form
            method="POST"
            action="/login"
            class="login-form"
        >
            @csrf

            <div class="form-group">

                <label for="email">
                    E-pasts
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="tavs@epasts.lv"
                    autocomplete="email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Parole
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Ievadi paroli"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button type="submit">
                Pieslēgties
            </button>

        </form>


        <p class="auth-footer">
            Nav konta?
            <a href="/register">
                Reģistrēties
            </a>
        </p>

    </section>

</main>

</body>
</html>