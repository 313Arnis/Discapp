<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trases - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>

    <div>
        <a href="{{ route('home') }}">Discapp</a>
    </div>

    <div class="nav-auth">

        <a href="{{ route('competitions.index') }}">
            Sacensības
        </a>

        <a href="{{ route('courses.index') }}">
            Trases
        </a>

        <a href="{{ route('profile') }}">
            Profils
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit">
                Iziet
            </button>
        </form>

    </div>

</nav>


<main class="profile-page">

    <div class="profile-header">

        <div>
            <h1>Disku golfa trases</h1>

            <p>
                Pārvaldi trases un to grozu PAR vērtības
            </p>
        </div>

        <div>
            <a
                href="{{ route('courses.create') }}"
                class="btn"
            >
                + Pievienot trasi
            </a>
        </div>

    </div>


    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    <section class="competitions">

        @forelse($courses as $course)

            <div class="competition">

                <div>

                    <h2>
                        {{ $course->name }}
                    </h2>

                    <p>
                        <strong>Grozu skaits:</strong>
                        {{ $course->holes }}
                    </p>

                    <p>
                        <strong>Kopējais PAR:</strong>
                        {{ $course->courseHoles()->sum('par') }}
                    </p>

                    <p>
                        <strong>1000 reitinga rezultāts:</strong>
                        {{ $course->rating_1000_score }}
                    </p>

                    <p>
                        <strong>Reitinga punkti par metienu:</strong>
                        {{ $course->rating_per_throw }}
                    </p>

                </div>


                <div style="
                    display: flex;
                    gap: 10px;
                    align-items: center;
                    flex-wrap: wrap;
                ">

                    <a
                        href="{{ route('courses.edit', $course) }}"
                        style="
                            display: inline-block;
                            padding: 10px 15px;
                            background: #222;
                            color: white;
                            text-decoration: none;
                            border-radius: 6px;
                        "
                    >
                        Rediģēt PAR
                    </a>


                    <form
                        action="{{ route('courses.destroy', $course) }}"
                        method="POST"
                        style="display: inline;"
                        onsubmit="return confirm('Vai tiešām vēlies dzēst šo trasi?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            style="
                                padding: 10px 15px;
                                background: #c0392b;
                                color: white;
                                border: none;
                                border-radius: 6px;
                                cursor: pointer;
                            "
                        >
                            Dzēst
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="competition">

                <p>
                    Šobrīd nav pievienota neviena trase.
                </p>

                <a
                    href="{{ route('courses.create') }}"
                    class="btn"
                >
                    + Pievienot pirmo trasi
                </a>

            </div>

        @endforelse

    </section>

</main>

</body>
</html>