<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lietotāji - Discapp</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<nav>

    <div>
        <a href="{{ route('home') }}">
            Discapp
        </a>
    </div>

    <div class="nav-auth">

        <a href="{{ route('admin') }}">
            Admin
        </a>

        <a href="{{ route('profile') }}">
            Profils
        </a>

        <form method="POST" action="{{ route('logout') }}">
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
            <h1>Lietotāji</h1>

            <p>
                Discapp lietotāju pārvaldība
            </p>
        </div>

        <div>

            <a
                href="{{ route('admin') }}"
                class="btn"
            >
                Atpakaļ
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="competition">

            @foreach($errors->all() as $error)

                <p>
                    {{ $error }}
                </p>

            @endforeach

        </div>

    @endif


    <section class="competitions">

        @forelse($users as $user)

            <div class="competition">

                <div>

                    <h2>
                        {{ $user->name }}
                    </h2>

                    <p>
                        <strong>E-pasts:</strong>
                        {{ $user->email }}
                    </p>

                    <p>
                        <strong>Reitings:</strong>
                        {{ $user->rating }}
                    </p>

                    <p>
                        <strong>Loma:</strong>
                        {{ $user->role }}
                    </p>

                </div>


                <div>

                    <form
                        method="POST"
                        action="{{ route('admin.users.update', $user) }}"
                    >

                        @csrf
                        @method('PUT')

                        <div style="margin-bottom: 10px;">

                            <label for="role_{{ $user->id }}">
                                Loma
                            </label>

                            <select
                                id="role_{{ $user->id }}"
                                name="role"
                            >

                                <option
                                    value="user"
                                    {{ $user->role === 'user' ? 'selected' : '' }}
                                >
                                    Lietotājs
                                </option>

                                <option
                                    value="admin"
                                    {{ $user->role === 'admin' ? 'selected' : '' }}
                                >
                                    Administrators
                                </option>

                            </select>

                        </div>


                        <div style="margin-bottom: 10px;">

                            <label for="rating_{{ $user->id }}">
                                Reitings
                            </label>

                            <input
                                type="number"
                                id="rating_{{ $user->id }}"
                                name="rating"
                                value="{{ $user->rating }}"
                                min="0"
                                max="2000"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn"
                        >
                            Saglabāt
                        </button>

                    </form>


                    @if($user->id !== auth()->id())

                        <form
                            method="POST"
                            action="{{ route('admin.users.destroy', $user) }}"
                            style="margin-top: 10px;"
                            onsubmit="return confirm('Vai tiešām vēlies dzēst šo lietotāju?')"
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
                                Dzēst lietotāju
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @empty

            <div class="competition">

                <p>
                    Sistēmā nav neviena lietotāja.
                </p>

            </div>

        @endforelse

    </section>

</main>

</body>
</html>