
@extends('layouts.app')

@section('title', 'Mans profils - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')

<main class="discapp-profile">

    {{-- PAZIŅOJUMI --}}
    @if(session('success'))
        <div class="dp-alert dp-alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="dp-alert dp-alert-error" role="alert">
            <strong>Radās kļūda:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PROFILA GALVENE --}}
    <section class="dp-hero">

        <div class="dp-hero-decoration"></div>

        <div class="dp-hero-content">

            <div class="dp-user">

                <div class="dp-avatar-wrap">

                    @if($user->profile_picture)
                        <img
                            src="{{ asset('storage/' . $user->profile_picture) }}"
                            alt="{{ $user->name }}"
                            class="dp-avatar"
                            id="profileImage"
                        >
                    @else
                        <div class="dp-avatar-fallback" id="profileAvatar">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>

                        <img
                            src=""
                            alt="{{ $user->name }}"
                            class="dp-avatar"
                            id="profileImage"
                            style="display: none;"
                        >
                    @endif

                </div>

                <div class="dp-user-details">
                    <span class="dp-eyebrow">MANS PROFILS</span>

                    <h1>{{ $user->name }}</h1>

                    <p class="dp-email">{{ $user->email }}</p>

                    <span class="dp-role">Spēlētājs</span>

                    <div class="dp-picture-controls">

                        <form
                            method="POST"
                            action="{{ route('profile.picture.update') }}"
                            enctype="multipart/form-data"
                            id="profilePictureForm"
                            class="dp-picture-form"
                        >
                            @csrf
                            @method('PUT')

                            <input
                                type="file"
                                name="profile_picture"
                                id="profile_picture"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="dp-file-input"
                                required
                            >

                            <label for="profile_picture" class="dp-btn dp-btn-outline">
                                Mainīt bildi
                            </label>

                            <span id="selectedFile" class="dp-selected-file"></span>

                            <button
                                type="submit"
                                id="savePictureButton"
                                class="dp-btn dp-btn-primary"
                            >
                            Saglabāt bildi
                            </button>
                        </form>

                        @if($user->profile_picture)
                            <form
                                method="POST"
                                action="{{ route('profile.picture.delete') }}"
                                onsubmit="return confirm('Vai tiešām vēlies noņemt profila bildi?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="dp-remove-picture">
                                    Noņemt bildi
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

            </div>

            <div class="dp-rating">
                <span>SPĒLĒTĀJA REITINGS</span>
                <strong>{{ $user->rating ?? '—' }}</strong>
                <small>Discapp Rating</small>
            </div>

        </div>
    </section>

    {{-- STATISTIKA --}}
    <section class="dp-stats">

        <div class="dp-stat-card">
            <span class="dp-stat-label">Sacensības</span>
            <strong>{{ $user->competitions->count() }}</strong>
            <span class="dp-stat-description">Pieteiktās sacensības</span>
        </div>

        <div class="dp-stat-card">
            <span class="dp-stat-label">Izspēlētās sacensības</span>
            <strong>{{ $playedCompetitions }}</strong>
            <span class="dp-stat-description">Pabeigtie turnīri</span>
        </div>

        <div class="dp-stat-card">
            <span class="dp-stat-label">Uzvaras</span>
            <strong>{{ $wins }}</strong>
            <span class="dp-stat-description">Iegūtās pirmās vietas</span>
        </div>

        <div class="dp-stat-card">
            <span class="dp-stat-label">Diski somā</span>
            <strong>{{ $user->discs->count() }}</strong>
            <span class="dp-stat-description">Tava kolekcija</span>
        </div>

    </section>

    {{-- PAR SPĒLĒTĀJU --}}
    <section class="dp-panel">

        <div class="dp-panel-header">
            <div>
                <span class="dp-eyebrow">INFORMĀCIJA</span>
                <h2>Par spēlētāju</h2>
            </div>
        </div>

        <div class="dp-info-grid">

            <div class="dp-info-item">
                <span>Vārds</span>
                <strong>{{ $user->name }}</strong>
            </div>

            <div class="dp-info-item">
                <span>E-pasts</span>
                <strong>{{ $user->email }}</strong>
            </div>

            <div class="dp-info-item">
                <span>Reitings</span>
                <strong>{{ $user->rating ?? '—' }}</strong>
            </div>

        </div>
    </section>

    {{-- SACENSĪBU VĒSTURE --}}
    <section class="dp-panel">

        <div class="dp-panel-header">
            <div>
                <span class="dp-eyebrow">TAVI REZULTĀTI</span>
                <h2>Sacensību vēsture</h2>
            </div>

            <span class="dp-count">
                {{ $competitionHistory->count() }}
            </span>
        </div>

        @if($competitionHistory->count() > 0)

            <div class="dp-table-wrap">
                <table class="dp-history-table">

                    <thead>
                        <tr>
                            <th>Sacensības</th>
                            <th>Datums</th>
                            <th>Divīzija</th>
                            <th>Rezultāts</th>
                            <th>Vieta</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($competitionHistory as $history)
                            <tr>
                                <td>
                                    <a
                                        href="{{ route('competitions.show', $history['competition']) }}"
                                        class="dp-history-link"
                                    >
                                        {{ $history['competition']->name }}
                                    </a>
                                </td>

                                <td>
                                    {{ $history['competition']->date
                                        ? \Carbon\Carbon::parse($history['competition']->date)->format('d.m.Y')
                                        : ($history['competition']->starts_at
                                            ? \Carbon\Carbon::parse($history['competition']->starts_at)->format('d.m.Y')
                                            : '-') }}
                                </td>

                                <td>
                                    <span class="dp-division">
                                        {{ $history['division'] }}
                                    </span>
                                </td>

                                <td>
                                    <strong class="dp-score">
                                        @if($history['relative_to_par'] === 0)
                                            E
                                        @elseif($history['relative_to_par'] > 0)
                                            +{{ $history['relative_to_par'] }}
                                        @else
                                            {{ $history['relative_to_par'] }}
                                        @endif
                                    </strong>
                                </td>

                                <td>
                                    <span class="dp-place">
                                        {{ $history['place'] }}.
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @else

            <div class="dp-empty">
                <div class="dp-empty-icon">🏆</div>
                <h3>Vēl nav sacensību rezultātu</h3>
                <p>Te parādīsies tavi pabeigto sacensību rezultāti.</p>

                <a href="{{ route('competitions.index') }}" class="dp-btn dp-btn-primary">
                    Apskatīt sacensības
                </a>
            </div>

        @endif

    </section>

    {{-- DISKU SOMA --}}
    <section class="dp-panel">

        <div class="dp-panel-header">
            <div>
                <span class="dp-eyebrow">TAVA KOLEKCIJA</span>
                <h2>Mana disku soma</h2>
            </div>

            <a
                href="{{ route('profile.discs.index') }}"
                class="dp-btn dp-btn-primary"
            >
                Pārvaldīt diskus
            </a>
        </div>

        @if($user->discs->count() > 0)

            <div class="dp-discs-grid">

                @foreach($user->discs as $disc)
                    <article class="dp-disc-card">

                        <div class="dp-disc-image-wrap">
                            @if($disc->image)
                                <img
                                    src="{{ asset('storage/' . $disc->image) }}"
                                    alt="{{ $disc->name }}"
                                    class="dp-disc-image"
                                >
                            @else
                                <div class="dp-disc-placeholder">🥏</div>
                            @endif
                        </div>

                        <div class="dp-disc-body">
                            <h3>{{ $disc->name }}</h3>

                            @if($disc->type)
                                <span class="dp-disc-type">
                                    {{ $disc->type }}
                                </span>
                            @endif

                            <div class="dp-flight-numbers">

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

                    </article>
                @endforeach

            </div>

        @else

            <div class="dp-empty">
                <div class="dp-empty-icon">🥏</div>

                <h3>Disku soma vēl ir tukša</h3>

                <p>
                    Pievieno savus diskus, lai vienuviet redzētu
                    visu savu disku golfa kolekciju.
                </p>

                <a
                    href="{{ route('profile.discs.index') }}"
                    class="dp-btn dp-btn-primary"
                >
                    Pievienot diskus
                </a>
            </div>

        @endif

    </section>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('profile_picture');
    const profileImage = document.getElementById('profileImage');
    const profileAvatar = document.getElementById('profileAvatar');
    const selectedFile = document.getElementById('selectedFile');
    const saveButton = document.getElementById('savePictureButton');

    if (!input || !profileImage || !selectedFile || !saveButton) {
        return;
    }

    input.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            selectedFile.textContent = '';
            selectedFile.classList.remove('visible');
            saveButton.classList.remove('visible');
            return;
        }

        selectedFile.textContent = file.name;
        selectedFile.classList.add('visible');
        saveButton.classList.add('visible');

        if (!file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            if (profileAvatar) {
                profileAvatar.style.display = 'none';
            }

            profileImage.src = event.target.result;
            profileImage.style.display = 'block';
        };

        reader.readAsDataURL(file);
    });
});
</script>
@endpush
