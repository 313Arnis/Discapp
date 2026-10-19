
@extends('layouts.app')

@section('title', 'Mani diski - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/disc-form.css') }}">
@endpush

@section('content')

<main class="disc-list-page">

    {{-- GALVENE --}}
    <div class="disc-list-header">

        <div>
            <span class="disc-form-eyebrow">MANA KOLEKCIJA</span>

            <h1>Mani diski</h1>

            <p>
                Pārvaldi savu disku golfa kolekciju.
                Tev ir {{ $discs->count() }} diski.
            </p>
        </div>

        <a
            href="{{ route('profile.discs.create') }}"
            class="disc-form-submit"
        >
            + Pievienot disku
        </a>

    </div>

    {{-- PAZIŅOJUMI --}}
    @if(session('success'))
        <div class="disc-list-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- DISKU SARAKSTS --}}
    @if($discs->count() > 0)

        <div class="disc-list-grid">

            @foreach($discs as $disc)

                <article class="disc-list-card">

                    <div class="disc-list-image">

                        @if($disc->image)
                            <img
                                src="{{ asset('storage/' . $disc->image) }}"
                                alt="{{ $disc->name }}"
                            >
                        @else
                            <span>🥏</span>
                        @endif

                        <span class="disc-list-type">
                            {{ $disc->type }}
                        </span>

                    </div>

                    <div class="disc-list-body">

                        <h2>{{ $disc->name }}</h2>

                        <div class="disc-list-stats">

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

                        <div class="disc-list-actions">

                            <a
                                href="{{ route('profile.discs.edit', $disc) }}"
                                class="disc-list-edit"
                            >
                                Rediģēt
                            </a>

                            <form
                                method="POST"
                                action="{{ route('profile.discs.destroy', $disc) }}"
                                onsubmit="return confirm('Vai tiešām vēlies dzēst šo disku?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="disc-list-delete"
                                >
                                    Dzēst
                                </button>

                            </form>

                        </div>

                    </div>

                </article>

            @endforeach

        </div>

    @else

        <div class="disc-list-empty">

            <div class="disc-list-empty-icon">🥏</div>

            <h2>Tava disku soma vēl ir tukša</h2>

            <p>
                Pievieno savu pirmo disku, lai sāktu
                veidot personīgo disku golfa kolekciju.
            </p>

            <a
                href="{{ route('profile.discs.create') }}"
                class="disc-form-submit"
            >
                + Pievienot pirmo disku
            </a>

        </div>

    @endif

    <div class="disc-list-footer">

        <a href="{{ route('profile') }}" class="disc-form-back">
            ← Atpakaļ uz profilu
        </a>

    </div>

</main>

@endsection
