
@extends('layouts.app')

@section('title', 'Pievienot trasi - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-courses.css') }}">
@endpush

@section('content')
<main class="courses-page courses-page-narrow">

    <div class="courses-header">
        <div>
            <span class="courses-eyebrow">DISCAPP ADMINISTRĀCIJA</span>
            <h1>Pievienot trasi</h1>
            <p>Izveido jaunu disku golfa trasi un norādi tās parametrus.</p>
        </div>

        <a href="{{ route('courses.index') }}" class="courses-btn-secondary">
            ← Atpakaļ
        </a>
    </div>

    @if($errors->any())
        <div class="courses-alert courses-alert-error" role="alert">
            <strong>Neizdevās izveidot trasi.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="courses-panel">
        <div class="courses-panel-heading">
            <span class="courses-panel-icon">⛳</span>
            <div>
                <h2>Trases informācija</h2>
                <p>Aizpildi nepieciešamos laukus.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('courses.store') }}" class="courses-form">
            @csrf

            <div class="courses-field">
                <label for="name">Trases nosaukums</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Piemēram, Mežaparka trase"
                    maxlength="255"
                    required
                >
                @error('name')
                    <span class="courses-field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="courses-field">
                <label for="holes">Grozu skaits</label>
                <input
                    type="number"
                    id="holes"
                    name="holes"
                    value="{{ old('holes', 18) }}"
                    min="1"
                    max="36"
                    required
                >
                <span class="courses-field-hint">
                    Iespējams norādīt no 1 līdz 36 groziem.
                </span>
            </div>

            <div class="courses-form-divider"></div>

            <h3 class="courses-form-subtitle">Reitinga parametri</h3>

            <div class="courses-fields-grid">
                <div class="courses-field">
                    <label for="rating_1000_score">
                        1000 reitinga rezultāts
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        id="rating_1000_score"
                        name="rating_1000_score"
                        value="{{ old('rating_1000_score') }}"
                        placeholder="Ievadi vērtību"
                        required
                    >
                </div>

                <div class="courses-field">
                    <label for="rating_per_throw">
                        Reitinga punkti par metienu
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        id="rating_per_throw"
                        name="rating_per_throw"
                        value="{{ old('rating_per_throw') }}"
                        placeholder="Piemēram, 10"
                        required
                    >
                </div>
            </div>

            <div class="courses-form-actions">
                <a href="{{ route('courses.index') }}" class="courses-btn-secondary">
                    Atcelt
                </a>

                <button type="submit" class="courses-btn-primary">
                    + Izveidot trasi
                </button>
            </div>
        </form>
    </section>

</main>
@endsection
