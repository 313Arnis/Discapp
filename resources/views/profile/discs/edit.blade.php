
@extends('layouts.app')

@section('title', 'Rediģēt disku - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/disc-form.css') }}">
@endpush

@section('content')

<main class="disc-form-page">

    <div class="disc-form-header">
        <div>
            <span class="disc-form-eyebrow">MANA DISKU SOMA</span>
            <h1>Rediģēt disku</h1>
            <p>Atjaunini diska informāciju un lidojuma parametrus.</p>
        </div>

        <a href="{{ route('profile.discs.index') }}" class="disc-form-back">
            ← Atpakaļ uz diskiem
        </a>
    </div>

    @if($errors->any())
        <div class="disc-form-alert" role="alert">
            <strong>Neizdevās saglabāt izmaiņas.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('profile.discs.update', $disc) }}"
        enctype="multipart/form-data"
        class="disc-create-form"
    >
        @csrf
        @method('PUT')

        {{-- DISKA INFORMĀCIJA --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">🥏</div>
                <div>
                    <h2>Diska informācija</h2>
                    <p>Maini diska nosaukumu vai tipu.</p>
                </div>
            </div>

            <div class="disc-form-fields">

                <div class="disc-form-field">
                    <label for="name">Diska nosaukums</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $disc->name) }}"
                        placeholder="Piemēram, Destroyer"
                        required
                    >

                    @error('name')
                        <span class="disc-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="disc-form-field">
                    <label for="type">Diska tips</label>

                    <select id="type" name="type" required>
                        <option value="Distance Driver"
                            @selected(old('type', $disc->type) === 'Distance Driver')>
                            Distance Driver
                        </option>

                        <option value="Fairway Driver"
                            @selected(old('type', $disc->type) === 'Fairway Driver')>
                            Fairway Driver
                        </option>

                        <option value="Midrange"
                            @selected(old('type', $disc->type) === 'Midrange')>
                            Midrange
                        </option>

                        <option value="Putter"
                            @selected(old('type', $disc->type) === 'Putter')>
                            Putter
                        </option>
                    </select>

                    @error('type')
                        <span class="disc-field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </section>

        {{-- LIDOJUMA PARAMETRI --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">📊</div>
                <div>
                    <h2>Diska lidojuma parametri</h2>
                    <p>Atjaunini diska Speed, Glide, Turn un Fade.</p>
                </div>
            </div>

            <div class="disc-stats-grid">

                <div class="disc-form-field">
                    <label for="speed">Speed</label>
                    <input
                        type="number"
                        id="speed"
                        name="speed"
                        value="{{ old('speed', $disc->speed) }}"
                        min="1"
                        max="15"
                        step="0.5"
                        required
                    >
                    <span class="disc-field-hint">Diska ātrums</span>
                </div>

                <div class="disc-form-field">
                    <label for="glide">Glide</label>
                    <input
                        type="number"
                        id="glide"
                        name="glide"
                        value="{{ old('glide', $disc->glide) }}"
                        min="1"
                        max="7"
                        step="0.5"
                        required
                    >
                    <span class="disc-field-hint">Planēšanas spēja</span>
                </div>

                <div class="disc-form-field">
                    <label for="turn">Turn</label>
                    <input
                        type="number"
                        id="turn"
                        name="turn"
                        value="{{ old('turn', $disc->turn) }}"
                        min="-5"
                        max="1"
                        step="0.5"
                        required
                    >
                    <span class="disc-field-hint">Sākotnējā novirze</span>
                </div>

                <div class="disc-form-field">
                    <label for="fade">Fade</label>
                    <input
                        type="number"
                        id="fade"
                        name="fade"
                        value="{{ old('fade', $disc->fade) }}"
                        min="0"
                        max="5"
                        step="0.5"
                        required
                    >
                    <span class="disc-field-hint">Noslēguma novirze</span>
                </div>

            </div>

        </section>

        {{-- DISKA ATTĒLS --}}
        <section class="disc-form-card">

            <div class="disc-form-section-heading">
                <div class="disc-form-section-icon">📷</div>
                <div>
                    <h2>Diska bilde</h2>
                    <p>Atstāj esošo attēlu vai izvēlies jaunu.</p>
                </div>
            </div>

            <div class="disc-image-upload">

                <div class="disc-image-preview">

                    @if($disc->image)
                        <img
                            src="{{ asset('storage/' . $disc->image) }}"
                            alt="{{ $disc->name }}"
                            id="discPreviewImage"
                        >
                    @else
                        <span id="discImagePlaceholder">🥏</span>
                        <img
                            id="discPreviewImage"
                            alt="Diska attēla priekšskatījums"
                            hidden
                        >
                    @endif

                </div>

                <div class="disc-image-upload-info">

                    <label for="image">Mainīt diska bildi</label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <p>
                        JPG, PNG vai WEBP. Ja neizvēlēsies jaunu
                        attēlu, pašreizējais paliks nemainīgs.
                    </p>

                    @error('image')
                        <span class="disc-field-error">{{ $message }}</span>
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

            <button type="submit" class="disc-form-submit">
                Saglabāt izmaiņas
            </button>

        </div>

    </form>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('image');
    const preview = document.getElementById('discPreviewImage');
    const placeholder = document.getElementById('discImagePlaceholder');

    if (!input || !preview) return;

    let previewUrl = null;

    input.addEventListener('change', function () {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        const file = this.files[0];

        if (!file || !file.type.startsWith('image/')) return;

        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.hidden = false;

        if (placeholder) {
            placeholder.style.display = 'none';
        }
    });
});
</script>
@endpush
