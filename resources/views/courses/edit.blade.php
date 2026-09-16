<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $course->name }} - Discapp</title>

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
            <h1>{{ $course->name }}</h1>

            <p>
                {{ $course->holes }} grozi
            </p>
        </div>

    </div>


    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif


    @if($errors->any())
        <div class="error-message">

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    <section class="card">

        <h2>Trases PAR</h2>

        <form
            action="{{ route('courses.update', $course) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="holes-grid">

@foreach($course->courseHoles as $hole)

    <div class="hole-card">

        <label for="par_{{ $hole->hole_number }}">
            Grozs {{ $hole->hole_number }}
        </label>

        <input
            type="number"
            id="par_{{ $hole->hole_number }}"
            name="par[{{ $hole->hole_number }}]"
            value="{{ old('par.' . $hole->hole_number, $hole->par) }}"
            min="2"
            max="6"
            required
        >

    </div>

@endforeach

</div>


            <button type="submit" class="btn">
                Saglabāt PAR
            </button>

        </form>

    </section>

</main>

</body>
</html>