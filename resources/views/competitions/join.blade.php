
@extends('layouts.app')

@section('title', 'Pievienoties sacensībām - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competition-join.css') }}">
@endpush

@section('content')

<div class="join-page">

    <div class="join-heading">
        <span class="join-eyebrow">DISCAPP SACENSĪBAS</span>

        <h1>Pievienoties sacensībām</h1>

        <p>
            Izvēlies savu divīziju un piesakies sacensībām.
        </p>
    </div>

    <div class="join-card">

        <div class="join-competition-info">
            <div class="join-competition-icon">
                <icon>★</icon>
            </div>

            <div>
                <span class="join-small-label">
                    SACENSĪBAS
                </span>

                <h2>{{ $competition->name }}</h2>
            </div>
        </div>

        <div class="join-rating">
            <div>
                <span class="join-rating-label">
                    Tavs pašreizējais reitings
                </span>

                <strong>{{ $rating ?? 'Nav reitinga' }}</strong>
            </div>

            <span class="join-rating-badge">
                PLAYER RATING
            </span>
        </div>

        <form
            method="POST"
            action="{{ route('competitions.join.store', $competition) }}"
            class="join-form"
        >
            @csrf

            <div class="join-form-group">
                <label for="division">
                    Izvēlies divīziju
                </label>

                <select
                    id="division"
                    name="division"
                    required
                    class="@error('division') is-invalid @enderror"
                >
                    <option value="">
                        Izvēlies divīziju
                    </option>

                    @foreach($divisions as $code => $name)
                        <option
                            value="{{ $code }}"
                            @selected(old('division') == $code)
                        >
                            {{ $name }}
                        </option>
                    @endforeach
                </select>

                @error('division')
                    <div class="join-error">
                        {{ $message }}
                    </div>
                @enderror

                <p class="join-field-hint">
                    Izvēlies divīziju, kurā vēlies piedalīties.
                </p>
            </div>

            <div class="join-actions">
                <button type="submit" class="join-submit">
                    Pieteikties sacensībām
                    <span aria-hidden="true">→</span>
                </button>

                <a
                    href="{{ route('competitions.show', $competition) }}"
                    class="join-cancel"
                >
                    Atcelt
                </a>
            </div>
        </form>

    </div>

    <div class="join-footer-note">
        Pēc pieteikšanās varēsi apskatīt savu dalību
        sacensību lapā.
    </div>

</div>

@endsection
