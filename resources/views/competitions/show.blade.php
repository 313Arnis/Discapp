<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $competition->name }} - DiscGolf</title>

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
        <a href="/profile">Mans profils</a>
    </div>

    @auth
        <div class="nav-auth">

            <span>
                Sveiks, {{ auth()->user()->name }}!
            </span>

            <form method="POST" action="/logout">
                @csrf

                <button type="submit">
                    Iziet
                </button>
            </form>

        </div>
    @endauth

</nav>


<main>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif


    <div class="competition-card">


        {{-- ======================================
             SACENSĪBU GALVENE
        ====================================== --}}

        <div class="competition-header">

            <div class="competition-title">

                <span class="competition-label">
                    SACENSĪBAS
                </span>

                <h1>
                    {{ $competition->name }}
                </h1>

                @if($competition->description)

                    <p class="competition-description">
                        {{ $competition->description }}
                    </p>

                @endif

            </div>


            @if($competition->max_players)

                @php

                    $participantsCount = $competition->users->count();

                    $percent = min(
                        100,
                        round(
                            ($participantsCount / $competition->max_players) * 100
                        )
                    );

                @endphp

                <div class="competition-capacity">

                    <span>
                        Pieteikušies
                    </span>

                    <strong>
                        {{ $participantsCount }}

                        <small>
                            / {{ $competition->max_players }}
                        </small>
                    </strong>

                </div>

            @endif

        </div>



        {{-- ======================================
             PAMATINFORMĀCIJA
        ====================================== --}}

        <div class="meta-grid">


            <div class="meta-card">

                <span class="meta-label">
                    📅 Datums
                </span>

                <strong>
                    {{ $competition->date }}
                </strong>

            </div>


            <div class="meta-card">

                <span class="meta-label">
                    📍 Vieta
                </span>

                <strong>
                    {{ $competition->location }}
                </strong>

            </div>


            <div class="meta-card">

                <span class="meta-label">
                    👥 Dalībnieki
                </span>

                <strong>
                    {{ $competition->users->count() }}
                </strong>

            </div>


            @if($competition->max_players)

                <div class="meta-card">

                    <span class="meta-label">
                        🏆 Maksimums
                    </span>

                    <strong>
                        {{ $competition->max_players }}
                    </strong>

                </div>

            @endif


        </div>



        {{-- ======================================
             VIETU AIZPILDĪJUMS
        ====================================== --}}

        @if($competition->max_players)

            <div class="capacity-section">

                <div class="capacity-top">

                    <span>
                        Vietu aizpildījums
                    </span>

                    <strong>
                        {{ $percent }}%
                    </strong>

                </div>

                <div class="progress-bar-container">

                    <div
                        class="progress-bar"
                        style="width: {{ $percent }}%;"
                    ></div>

                </div>

            </div>

        @endif



        <hr class="divider">



        {{-- ======================================
             DALĪBNIEKI
        ====================================== --}}

        <div class="section-title-wrapper">

            <div>

                <span class="section-label">
                    PIETEIKTIE SPĒLĒTĀJI
                </span>

                <h2>
                    Dalībnieki
                </h2>

            </div>


            <span class="badge">
                {{ $competition->users->count() }}
            </span>

        </div>



        @if($competition->users->count() > 0)


            <div class="participants">


                @foreach(
                    $competition->users->groupBy('pivot.division')
                    as $division => $players
                )


                    <div class="division-group">


                        {{-- DIVĪZIJAS GALVENE --}}

                        <div class="division-header">

                            <div>

                                <span class="division-label">
                                    DIVĪZIJA
                                </span>

                                <h3>
                                    {{ $division }}
                                </h3>

                            </div>


                            <span class="division-count">

                                {{ $players->count() }}

                                @if($players->count() == 1)
                                    spēlētājs
                                @else
                                    spēlētāji
                                @endif

                            </span>

                        </div>



                        {{-- SPĒLĒTĀJI --}}

                        <div class="participants-grid">


                            @foreach($players as $player)


                                <div class="participant-card">


                                    <div class="participant-avatar">

                                        {{ strtoupper(
                                            substr($player->name, 0, 1)
                                        ) }}

                                    </div>


                                    <div class="participant-info">

                                        <strong>
                                            {{ $player->name }}
                                        </strong>

                                        <span class="participant-division-tag">
                                            {{ $player->pivot->division }}
                                        </span>

                                    </div>


                                </div>


                            @endforeach


                        </div>


                    </div>


                @endforeach


            </div>


        @else


            <div class="empty-state">

                <div class="empty-icon">
                    👤
                </div>

                <h3>
                    Vēl nav dalībnieku
                </h3>

                <p>
                    Šobrīd šīm sacensībām neviens nav pieteicies.
                </p>

            </div>


        @endif



        <hr class="divider">



        {{-- ======================================
             DARBĪBAS
        ====================================== --}}

        <div class="competition-actions">


            @auth


                @if($competition->users->contains(auth()->id()))


                    <form
                        method="POST"
                        action="{{ route(
                            'competitions.leave',
                            $competition
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="button"
                        >
                            Izstāties
                        </button>

                    </form>


                @else


                    <a
                        href="{{ route(
                            'competitions.join',
                            $competition
                        ) }}"
                        class="button"
                    >
                        Pievienoties sacensībām
                    </a>


                @endif


            @endauth



            <a
                href="{{ route('competitions.index') }}"
                class="secondary-button"
            >
                Atpakaļ
            </a>


        </div>



        {{-- ======================================
             SACENSĪBU PĀRVALDĪBA
        ====================================== --}}

        @auth

            <div class="competition-admin-actions">


                <a
                    href="{{ route(
                        'competitions.edit',
                        $competition
                    ) }}"
                    class="edit-link"
                >
                    Rediģēt sacensības
                </a>


                <form
                    method="POST"
                    action="{{ route(
                        'competitions.destroy',
                        $competition
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Vai tiešām vēlies dzēst šīs sacensības?'
                        );
                    "
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="delete-link"
                    >
                        Dzēst sacensības
                    </button>

                </form>


            </div>

        @endauth


    </div>

</main>

</body>
</html>