<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pievienot trasi - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>
    <div>
        <a href="/">Discapp</a>
    </div>

    <div class="nav-auth">
        <a href="/admin">Admin</a>
        <a href="{{ route('courses.index') }}">Trases</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Iziet</button>
        </form>
    </div>
</nav>

<div class="profile-page">

    <div class="profile-header">
        <h1>Pievienot trasi</h1>
        <p>Izveido jaunu disku golfa trasi.</p>
    </div>

    @if ($errors->any())
        <div class="competition">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="competition">

        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <div>
                <label for="name">Trases nosaukums</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <br>

            <div>
                <label for="holes">Grozu skaits</label>

                <input
                    type="number"
                    id="holes"
                    name="holes"
                    value="{{ old('holes', 18) }}"
                    min="1"
                    max="36"
                    required
                >
            </div>

            <br>

            <div>
                <label for="rating_1000_score">
                    1000 reitinga rezultāts
                </label>

                <input
                    type="number"
                    step="0.01"
                    id="rating_1000_score"
                    name="rating_1000_score"
                    value="{{ old('rating_1000_score') }}"
                    required
                >
            </div>

            <br>

            <div>
                <label for="rating_per_throw">
                    Reitinga punkti par metienu
                </label>

                <input
                    type="number"
                    step="0.01"
                    id="rating_per_throw"
                    name="rating_per_throw"
                    value="{{ old('rating_per_throw') }}"
                    required
                >
            </div>

            <br>

            <button type="submit">
                Izveidot trasi
            </button>

        </form>

    </div>

</div>

</body>
</html>