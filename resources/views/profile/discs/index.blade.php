<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mani diski - DiscGolf</title>

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

    <h1>Mani diski</h1>

    <a href="{{ route('profile.discs.create') }}" class="button">
        + Pievienot disku
    </a>

    @if(session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif

    @if($discs->count() > 0)

        <ul>

            @foreach($discs as $disc)

                <li>
                    <strong>{{ $disc->name }}</strong>
                    - {{ $disc->type }}

                    <form
                        action="{{ route('profile.discs.destroy', $disc) }}"
                        method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Vai tiešām vēlies dzēst šo disku?');"
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-button">
                            Dzēst
                        </button>

                    </form>
                </li>

            @endforeach

        </ul>

    @else

        <p>Tev vēl nav pievienotu disku.</p>

    @endif

    <br>

    <a href="/profile" class="button">
        Atpakaļ uz profilu
    </a>

</main>

</body>
</html>