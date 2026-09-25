<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mans profils - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>

        /*
        |--------------------------------------------------------------------------
        | PROFILA BILDE
        |--------------------------------------------------------------------------
        */

        .profile-picture-wrapper {
            position: relative;
            flex-shrink: 0;
        }


        .profile-picture {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        }


        .profile-avatar {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | BILDES REDIĢĒŠANAS POGA
        |--------------------------------------------------------------------------
        */

        .profile-picture-edit {
            position: absolute;
            right: -3px;
            bottom: -3px;

            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: 3px solid white;
            border-radius: 50%;

            background: #222;
            color: white;

            font-size: 15px;
            cursor: pointer;
        }


        .profile-picture-edit:hover {
            background: #000;
        }


        /*
        |--------------------------------------------------------------------------
        | PROFILA BILDES FORMA
        |--------------------------------------------------------------------------
        */

        .profile-picture-settings {
            margin-top: 20px;
            padding: 18px;
            background: #f8f8f8;
            border: 1px solid #e3e3e3;
            border-radius: 7px;
        }


        .profile-picture-settings h3 {
            margin: 0 0 5px;
        }


        .profile-picture-settings p {
            margin: 0 0 15px;
            color: #666;
            font-size: 14px;
        }


        .profile-picture-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }


        .profile-picture-input {
            display: none;
        }


        .choose-picture-button {
            display: inline-block;
            padding: 10px 16px;
            background: #222;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }


        .choose-picture-button:hover {
            background: #000;
        }


        .selected-file {
            color: #666;
            font-size: 14px;
        }


        .remove-picture-button {
            border: none;
            background: transparent;
            color: #c0392b;
            cursor: pointer;
            font-weight: bold;
            padding: 10px;
        }


        .remove-picture-button:hover {
            text-decoration: underline;
        }


        /*
        |--------------------------------------------------------------------------
        | PRIEKŠSKATĪJUMS
        |--------------------------------------------------------------------------
        */

        .profile-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            display: none;
        }

    </style>
</head>

<body>

<nav>

    <h2>
        DiscGolf
    </h2>

    <div>
        <a href="/">Sākums</a>

        <a href="{{ route('competitions.index') }}">
            Sacensības
        </a>

        <a href="{{ route('profile') }}">
            Mans profils
        </a>
    </div>


    <div class="nav-auth">

        <span>
            Sveiks, {{ $user->name }}!
        </span>


        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button type="submit">
                Iziet
            </button>

        </form>

    </div>

</nav>


<main class="profile-page">


    {{-- ======================================
         PAZIŅOJUMI
    ====================================== --}}

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    {{-- ======================================
         PROFILA GALVENE
    ====================================== --}}

    <div class="profile-header">

        <div class="profile-user">


            {{-- PROFILA BILDE --}}

            <div class="profile-picture-wrapper">

                @if($user->profile_picture)

                    <img
                        src="{{ asset(
                            'storage/' .
                            $user->profile_picture
                        ) }}"
                        alt="{{ $user->name }}"
                        class="profile-picture"
                        id="mainProfilePicture"
                    >

                @else

                    <div
                        class="profile-avatar"
                        id="mainProfileAvatar"
                    >
                        {{ strtoupper(
                            substr(
                                $user->name,
                                0,
                                1
                            )
                        ) }}
                    </div>

                @endif


                <label
                    for="profile_picture"
                    class="profile-picture-edit"
                    title="Mainīt profila bildi"
                >
                    ✎
                </label>

            </div>


            <div>

                <h1>
                    {{ $user->name }}
                </h1>

                <p>
                    {{ $user->email }}
                </p>

                <span class="profile-status">
                    Spēlētājs
                </span>

            </div>

        </div>


        <div class="profile-rating">

            <span>
                Reitings
            </span>

            <strong>
                {{ $user->rating }}
            </strong>

        </div>

    </div>


    {{-- ======================================
         PROFILA BILDES IESTATĪJUMI
    ====================================== --}}

    <div class="profile-picture-settings">

        <h3>
            Profila bilde
        </h3>

        <p>
            Izvēlies JPG, PNG vai WEBP attēlu.
            Maksimālais izmērs ir 5 MB.
        </p>


        <form
            method="POST"
            action="{{ route(
                'profile.picture.update'
            ) }}"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            <img
                id="profilePreview"
                class="profile-preview"
                alt="Profila bildes priekšskatījums"
            >


            <div class="profile-picture-actions">

                <input
                    type="file"
                    id="profile_picture"
                    name="profile_picture"
                    class="profile-picture-input"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >


                <label
                    for="profile_picture"
                    class="choose-picture-button"
                >
                    Izvēlēties bildi
                </label>


                <span
                    id="selectedFile"
                    class="selected-file"
                >
                    Bilde nav izvēlēta
                </span>


                <button
                    type="submit"
                    class="button"
                >
                    Saglabāt bildi
                </button>

            </div>

        </form>


        @if($user->profile_picture)

            <form
                method="POST"
                action="{{ route(
                    'profile.picture.delete'
                ) }}"
                onsubmit="
                    return confirm(
                        'Vai tiešām vēlies noņemt profila bildi?'
                    );
                "
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="remove-picture-button"
                >
                    Noņemt profila bildi
                </button>

            </form>

        @endif

    </div>


    {{-- ======================================
         STATISTIKA
    ====================================== --}}

    <div class="profile-stats">

        <div class="stat-box">

            <span>
                Sacensības
            </span>

            <strong>
                {{ $user->competitions->count() }}
            </strong>

        </div>


        <div class="stat-box">

            <span>
                Izspēlētās sacensības
            </span>

            <strong>
                {{ $playedCompetitions }}
            </strong>

        </div>


        <div class="stat-box">

            <span>
                Uzvaras
            </span>

            <strong>
                {{ $wins }}
            </strong>

        </div>


        <div class="stat-box">

            <span>
                Diski somā
            </span>

            <strong>
                {{ $user->discs->count() }}
            </strong>

        </div>

    </div>


    <div class="profile-content">


        {{-- ======================================
             PAR SPĒLĒTĀJU
        ====================================== --}}

        <div class="profile-section">

            <div class="section-header">

                <h2>
                    Par spēlētāju
                </h2>

            </div>


            <div class="profile-info">

                <div>

                    <span>
                        Vārds
                    </span>

                    <strong>
                        {{ $user->name }}
                    </strong>

                </div>


                <div>

                    <span>
                        E-pasts
                    </span>

                    <strong>
                        {{ $user->email }}
                    </strong>

                </div>


                <div>

                    <span>
                        Reitings
                    </span>

                    <strong>
                        {{ $user->rating }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- ======================================
             DISKU SOMA
        ====================================== --}}

        <div class="profile-section">

            <div class="section-header">

                <h2>
                    Mana disku soma
                </h2>

                <a
                    href="{{ route(
                        'profile.discs.index'
                    ) }}"
                    class="button"
                >
                    Pārvaldīt diskus
                </a>

            </div>


            @if($user->discs->count() > 0)

                <div class="discs">

                    @foreach($user->discs as $disc)

                        <div class="disc">

                            @if($disc->image)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $disc->image
                                    ) }}"
                                    alt="{{ $disc->name }}"
                                    class="disc-image"
                                >

                            @else

                                <div class="disc-no-image">
                                    🥏
                                </div>

                            @endif


                            <h3>
                                {{ $disc->name }}
                            </h3>


                            @if($disc->type)

                                <p>
                                    <strong>
                                        Tips:
                                    </strong>

                                    {{ $disc->type }}
                                </p>

                            @endif


                            <div class="flight-numbers">

                                <div>

                                    <span>
                                        Speed
                                    </span>

                                    <strong>
                                        {{ $disc->speed }}
                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Glide
                                    </span>

                                    <strong>
                                        {{ $disc->glide }}
                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Turn
                                    </span>

                                    <strong>
                                        {{ $disc->turn }}
                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Fade
                                    </span>

                                    <strong>
                                        {{ $disc->fade }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="bag-empty">

                    <div class="bag-icon">
                        🥏
                    </div>

                    <h3>
                        Disku soma vēl ir tukša
                    </h3>

                    <p>
                        Pievieno savus diskus profilam,
                        lai vienuviet redzētu savu
                        disku golfa somas saturu.
                    </p>

                    <a
                        href="{{ route(
                            'profile.discs.index'
                        ) }}"
                        class="button"
                    >
                        Pievienot diskus
                    </a>

                </div>

            @endif

        </div>

    </div>

</main>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const input =
                document.getElementById(
                    'profile_picture'
                );

            const preview =
                document.getElementById(
                    'profilePreview'
                );

            const selectedFile =
                document.getElementById(
                    'selectedFile'
                );


            if (!input) {
                return;
            }


            input.addEventListener(
                'change',
                function () {

                    const file =
                        this.files[0];


                    if (!file) {

                        selectedFile.textContent =
                            'Bilde nav izvēlēta';

                        preview.style.display =
                            'none';

                        preview.src = '';

                        return;
                    }


                    selectedFile.textContent =
                        file.name;


                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            preview.src =
                                event.target.result;

                            preview.style.display =
                                'block';

                        };


                    reader.readAsDataURL(file);

                }
            );

        }
    );

</script>


</body>

</html>