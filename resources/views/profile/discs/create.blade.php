
@extends('layouts.app')

@section('title', 'Pievienot disku - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/disc-form.css') }}">
@endpush

@section('content')

<main class="disc-form-page">

    {{-- LAPAS GALVENE --}}
    <div class="disc-form-header">

        <div>
            <span class="disc-form-eyebrow">
                MANA DISKU SOMA
            </span>

            <h1>Pievienot disku</h1>

            <p>
                Pievieno jaunu disku savai kolekcijai
                un norādi tā lidojuma parametrus.
            </p>
        </div>

        <a
            href="{{ route('profile.discs.index') }}"
            class="disc-form-back"
        >
            ← Atpakaļ uz diskiem
        </a>

    </div>

    {{-- KĻŪDU PAZIŅOJUMI --}}
    @if($errors->any())
        <div class="disc-form-alert" role="alert">
            <strong>Neizdevās pievienot disku.</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- DISKA PIEVIENOŠANAS FORMA --}}
    <form
        method="POST"
        action="{{ route('profile.discs.store') }}"
        enctype="multipart/form-data"
        class="disc-create-form"
    >
        @csrf

        {{-- PAMATINFORMĀCIJA --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">
                    🥏
                </div>

                <div>
                    <h2>Diska informācija</h2>
                    <p>Norādi diska nosaukumu un tipu.</p>
                </div>
            </div>

            <div class="disc-form-fields">

                <div class="disc-form-field">
                    <label for="name">
                        Diska nosaukums
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Piemēram, Destroyer"
                        required
                    >

                    @error('name')
                        <span class="disc-field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="disc-form-field">
                    <label for="type">
                        Diska tips
                    </label>

                    <select id="type" name="type" required>
                        <option value="">
                            Izvēlies diska tipu
                        </option>

                        <option
                            value="Distance Driver"
                            @selected(old('type') === 'Distance Driver')
                        >
                            Distance Driver
                        </option>

                        <option
                            value="Fairway Driver"
                            @selected(old('type') === 'Fairway Driver')
                        >
                            Fairway Driver
                        </option>

                        <option
                            value="Midrange"
                            @selected(old('type') === 'Midrange')
                        >
                            Midrange
                        </option>

                        <option
                            value="Putter"
                            @selected(old('type') === 'Putter')
                        >
                            Putter
                        </option>
                    </select>

                    @error('type')
                        <span class="disc-field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

        </section>

        {{-- LIDOJUMA PARAMETRI --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">
                    📊
                </div>

                <div>
                    <h2>Diska lidojuma parametri</h2>

                    <p>
                        Ievadi Speed, Glide, Turn un Fade vērtības.
                    </p>
                </div>
            </div>

            <div class="disc-stats-grid">

                <div class="disc-form-field">
                    <label for="speed">Speed</label>

                    <input
                        type="number"
                        id="speed"
                        name="speed"
                        value="{{ old('speed') }}"
                        min="1"
                        max="15"
                        step="0.5"
                        placeholder="12"
                        required
                    >

                    <span class="disc-field-hint">
                        Diska ātrums
                    </span>
                </div>

                <div class="disc-form-field">
                    <label for="glide">Glide</label>

                    <input
                        type="number"
                        id="glide"
                        name="glide"
                        value="{{ old('glide') }}"
                        min="1"
                        max="7"
                        step="0.5"
                        placeholder="5"
                        required
                    >

                    <span class="disc-field-hint">
                        Planēšanas spēja
                    </span>
                </div>

                <div class="disc-form-field">
                    <label for="turn">Turn</label>

                    <input
                        type="number"
                        id="turn"
                        name="turn"
                        value="{{ old('turn') }}"
                        min="-5"
                        max="1"
                        step="0.5"
                        placeholder="-1"
                        required
                    >

                    <span class="disc-field-hint">
                        Sākotnējā novirze
                    </span>
                </div>

                <div class="disc-form-field">
                    <label for="fade">Fade</label>

                    <input
                        type="number"
                        id="fade"
                        name="fade"
                        value="{{ old('fade') }}"
                        min="0"
                        max="5"
                        step="0.5"
                        placeholder="3"
                        required
                    >

                    <span class="disc-field-hint">
                        Noslēguma novirze
                    </span>
                </div>

            </div>

            <div class="disc-stats-example">
                <span>Piemērs:</span>

                <strong>12</strong>
                <strong>5</strong>
                <strong>-1</strong>
                <strong>3</strong>
            </div>

        </section>

        {{-- DISKA ATTĒLS --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">
                    📷
                </div>

                <div>
                    <h2>Diska bilde</h2>

                    <p>
                        Pievieno diska attēlu no savas ierīces.
                    </p>
                </div>
            </div>

            <div class="disc-image-upload">

                <div class="disc-image-preview" id="discImagePreview">
                    <span id="discImagePlaceholder">🥏</span>

                    <img
                        id="discPreviewImage"
                        alt="Diska attēla priekšskatījums"
                        hidden
                    >
                </div>

                <div class="disc-image-upload-info">

                    <label for="image">
                        Izvēlies diska bildi
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <p>
                        Atļautie formāti: JPG, PNG un WEBP.
                        Attēla pievienošana nav obligāta.
                    </p>

                    @error('image')
                        <span class="disc-field-error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

        </section>

        {{-- DARBĪBAS --}}
        <div class="disc-form-actions">

            <a
                href="{{ route('profile.discs.index') }}"
                class="disc-form-cancel"
            >
                Atcelt
            </a>

            <button
                type="submit"
                class="disc-form-submit"
            >
                + Pievienot disku
            </button>

        </div>

    </form>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('discPreviewImage');
    const placeholder = document.getElementById('discImagePlaceholder');

    if (!imageInput || !previewImage || !placeholder) {
        return;
    }

    let previewUrl = null;

    imageInput.addEventListener('change', function () {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        const file = this.files[0];

        if (!file || !file.type.startsWith('image/')) {
            previewImage.hidden = true;
            previewImage.removeAttribute('src');
            placeholder.style.display = 'block';
            return;
        }

        previewUrl = URL.createObjectURL(file);

        previewImage.src = previewUrl;
        previewImage.hidden = false;
        placeholder.style.display = 'none';
    });
});
</script>
@endpush
