<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mans profils - DiscGolf</title>

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

    <div class="nav-auth">
        <span>Sveiks, {{ $user->name }}!</span>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Iziet</button>
        </form>
    </div>
</nav>


<main class="profile-page">

    <div class="profile-header">

        <div class="profile-user">

            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <span class="profile-status">Spēlētājs</span>
            </div>

        </div>


        <div class="profile-rating">
            <span>Reitings</span>
            <strong>865</strong>
        </div>

    </div>


    <div class="profile-stats">

        <div class="stat-box">
            <span>Sacensības</span>
            <strong>0</strong>
        </div>

        <div class="stat-box">
            <span>Izspēlētās sacensības</span>
            <strong>0</strong>
        </div>

        <div class="stat-box">
            <span>Uzvaras</span>
            <strong>0</strong>
        </div>

        <div class="stat-box">
            <span>Diski somā</span>
            <strong>{{ $user->discs->count() }}</strong>
        </div>

    </div>


    <div class="profile-content">

        <div class="profile-section">

            <div class="section-header">
                <h2>Par spēlētāju</h2>
            </div>

            <div class="profile-info">

                <div>
                    <span>Vārds</span>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div>
                    <span>E-pasts</span>
                    <strong>{{ $user->email }}</strong>
                </div>

            </div>

        </div>


        <div class="profile-section">

            <div class="section-header">

                <h2>Mana disku soma</h2>

                <a href="{{ route('profile.discs.index') }}" class="button">
                    Pārvaldīt diskus
                </a>

            </div>


            @if($user->discs->count() > 0)

                <div class="discs">

                    @foreach($user->discs as $disc)

                        <div class="disc">

                            @if($disc->image)

                                <img
                                    src="{{ asset('storage/' . $disc->image) }}"
                                    alt="{{ $disc->name }}"
                                    class="disc-image"
                                >

                            @else

                                <div class="disc-no-image">
                                    🥏
                                </div>

                            @endif


                            <h3>{{ $disc->name }}</h3>


                            @if($disc->type)
                                <p>
                                    <strong>Tips:</strong>
                                    {{ $disc->type }}
                                </p>
                            @endif


                            <div class="flight-numbers">

                                <div>
                                    <span>Speed</span>
                                    <strong>{{ $disc->speed }}</strong>
                                </div>

                                <div>
                                    <span>Glide</span>
                                    <strong>{{ $disc->glide }}</strong>
                                </div>

                                <div>
                                    <span>Turn</span>
                                    <strong>{{ $disc->turn }}</strong>
                                </div>

                                <div>
                                    <span>Fade</span>
                                    <strong>{{ $disc->fade }}</strong>
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="bag-empty">

                    <div class="bag-icon">🥏</div>

                    <h3>Disku soma vēl ir tukša</h3>

                    <p>
                        Pievieno savus diskus profilam, lai vienuviet
                        redzētu savu disku golfa somas saturu.
                    </p>

                    <a href="{{ route('profile.discs.index') }}" class="button">
                        Pievienot diskus
                    </a>

                </div>

            @endif

        </div>

    </div>

</main>

</body>
</html>