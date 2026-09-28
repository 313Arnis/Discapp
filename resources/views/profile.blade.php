<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mans profils - DiscGolf</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>

        /* =====================================================
           PROFILA GALVENE
        ===================================================== */

        .profile-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }


        /* =====================================================
           PROFILA BILDE
        ===================================================== */

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

            background: #e8e8e8;
            color: #333;

            font-size: 35px;
            font-weight: bold;

            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        }


        /* =====================================================
           PROFILA INFORMĀCIJA + BILDES POGAS
        ===================================================== */

        .profile-user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-user-info h1 {
            margin: 0 0 5px;
        }

        .profile-user-info p {
            margin: 0 0 8px;
        }

        .profile-picture-form {
            margin-top: 12px;
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


        /* =====================================================
           IZVĒLĒTIES BILDI
        ===================================================== */

        .choose-picture-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 9px 15px;

            background: #222;
            color: white;

            border: none;
            border-radius: 5px;

            cursor: pointer;
            font-size: 14px;
            font-weight: bold;

            text-decoration: none;
        }

        .choose-picture-button:hover {
            background: #000;
        }


        /* =====================================================
           SAGLABĀT BILDI
        ===================================================== */

        .save-picture-button {
            display: none;

            padding: 9px 15px;

            background: #ff6500;
            color: white;

            border: none;
            border-radius: 5px;

            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .save-picture-button:hover {
            background: #e95700;
        }

        .save-picture-button.visible {
            display: inline-block;
        }


        /* =====================================================
           FAILA NOSAUKUMS
        ===================================================== */

        .selected-file {
            display: none;
            max-width: 180px;

            color: #666;
            font-size: 13px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .selected-file.visible {
            display: inline-block;
        }


        /* =====================================================
           NOŅEMT PROFILA BILDI
        ===================================================== */

        .remove-picture-form {
            margin: 0;
        }

        .remove-picture-button {
            padding: 9px 12px;

            background: transparent;
            color: #c0392b;

            border: none;

            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }

        .remove-picture-button:hover {
            text-decoration: underline;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 650px) {

            .profile-user {
                align-items: flex-start;
            }

            .profile-picture {
                width: 80px;
                height: 80px;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
                font-size: 30px;
            }

            .profile-picture-actions {
                gap: 6px;
            }

            .choose-picture-button,
            .save-picture-button {
                padding: 8px 10px;
                font-size: 12px;
            }

            .selected-file {
                max-width: 120px;
            }

        }

    </style>
</head>

<body>

<nav>

    <h2>
        DiscGolf
    </h2>

    <div>

        <a href="/">
            Sākums
        </a>

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


    {{-- =====================================================
         PAZIŅOJUMI
    ===================================================== --}}

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


    {{-- =====================================================
         PROFILA GALVENE
    ===================================================== --}}

    <div class="profile-header">


        <div class="profile-user">


            {{-- =================================================
                 PROFILA BILDE
            ================================================= --}}

            <div class="profile-picture-wrapper">

                @if($user->profile_picture)

                    <img
                        src="{{ asset('storage/' . $user->profile_picture) }}"
                        alt="{{ $user->name }}"
                        class="profile-picture"
                        id="profileImage"
                    >

                @else

                    <div
                        class="profile-avatar"
                        id="profileAvatar"
                    >
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <img
                        src=""
                        alt="{{ $user->name }}"
                        class="profile-picture"
                        id="profileImage"
                        style="display: none;"
                    >

                @endif

            </div>


            {{-- =================================================
                 VĀRDS + BILDES PIEVIENOŠANA
            ================================================= --}}

            <div class="profile-user-info">

                <h1>
                    {{ $user->name }}
                </h1>

                <p>
                    {{ $user->email }}
                </p>

                <span class="profile-status">
                    Spēlētājs
                </span>


                {{-- =============================================
                     BILDES FORMA
                ============================================= --}}

                <form
                    method="POST"
                    action="{{ route('profile.picture.update') }}"
                    enctype="multipart/form-data"
                    class="profile-picture-form"
                    id="profilePictureForm"
                >

                    @csrf

                    @method('PUT')


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
                            Mainīt bildi
                        </label>


                        <span
                            id="selectedFile"
                            class="selected-file"
                        >
                        </span>


                        <button
                            type="submit"
                            id="savePictureButton"
                            class="save-picture-button"
                        >
                            Saglabāt
                        </button>

                    </div>

                </form>


                {{-- =============================================
                     BILDES DZĒŠANA
                ============================================= --}}

                @if($user->profile_picture)

                    <form
                        method="POST"
                        action="{{ route('profile.picture.delete') }}"
                        class="remove-picture-form"
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
                            Noņemt bildi
                        </button>

                    </form>

                @endif

            </div>

        </div>


        {{-- =================================================
             REITINGS
        ================================================= --}}

        <div class="profile-rating">

            <span>
                Reitings
            </span>

            <strong>
                {{ $user->rating }}
            </strong>

        </div>

    </div>


    {{-- =====================================================
         STATISTIKA
    ===================================================== --}}

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


        {{-- =====================================================
             PAR SPĒLĒTĀJU
        ===================================================== --}}

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


        {{-- =====================================================
             DISKU SOMA
        ===================================================== --}}

        <div class="profile-section">

            <div class="section-header">

                <h2>
                    Mana disku soma
                </h2>

                <a
                    href="{{ route('profile.discs.index') }}"
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
                                    src="{{ asset('storage/' . $disc->image) }}"
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
                        href="{{ route('profile.discs.index') }}"
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
                document.getElementById('profile_picture');

            const profileImage =
                document.getElementById('profileImage');

            const profileAvatar =
                document.getElementById('profileAvatar');

            const selectedFile =
                document.getElementById('selectedFile');

            const saveButton =
                document.getElementById('savePictureButton');


            if (!input) {
                return;
            }


            input.addEventListener(
                'change',
                function () {

                    const file = this.files[0];


                    if (!file) {

                        selectedFile.textContent = '';

                        selectedFile.classList.remove(
                            'visible'
                        );

                        saveButton.classList.remove(
                            'visible'
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PARĀDĀM FAILA NOSAUKUMU
                    |--------------------------------------------------------------------------
                    */

                    selectedFile.textContent =
                        file.name;

                    selectedFile.classList.add(
                        'visible'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PARĀDĀM SAGLABĀŠANAS POGU
                    |--------------------------------------------------------------------------
                    */

                    saveButton.classList.add(
                        'visible'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PROFILA BILDES PRIEKŠSKATĪJUMS
                    |--------------------------------------------------------------------------
                    */

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            if (profileAvatar) {

                                profileAvatar.style.display =
                                    'none';

                            }


                            profileImage.src =
                                event.target.result;

                            profileImage.style.display =
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