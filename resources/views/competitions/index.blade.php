
@extends('layouts.app')

@section('title', 'Sacensības - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competitions.css') }}">
@endpush

@section('content')
<div class="competitions-page">
    <section class="competitions-header">
        <div class="competitions-header-text">
            <span class="page-badge">SACENSĪBAS</span>
            <h1>Disku golfa sacensības</h1>
            <p>Apskati pieejamās sacensības, pievienojies spēlei un seko līdzi rezultātiem.</p>
        </div>

        @auth
            <a class="create-competition-button" href="{{ route('competitions.create') }}">
                + Izveidot sacensības
            </a>
        @endauth
    </section>

    <section class="competition-list">
        @forelse($competitions as $competition)
            <article class="competition-card">
                <div class="competition-card-content">
                    <h2>{{ $competition->name }}</h2>

                    @if($competition->description)
                        <p class="competition-description">
                            {{ $competition->description }}
                        </p>
                    @endif

                    <div class="competition-meta">
                        <div class="competition-meta-item">
                            <span>📅</span>
                            <span>
                                <strong>Datums:</strong>
                                {{ $competition->date }}
                            </span>
                        </div>

                        <div class="competition-meta-item">
                            <span>📍</span>
                            <span>
                                <strong>Vieta:</strong>
                                {{ $competition->course?->name ?? 'Nav norādīta' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="competition-card-action">
                    <a href="{{ route('competitions.show', $competition) }}"
                       class="view-competition-button">
                        Skatīt sacensības →
                    </a>
                </div>
            </article>
        @empty
            <div class="empty-competitions">
                <div class="empty-icon">🥏</div>
                <h2>Sacensību pašlaik nav</h2>
                <p>Kad tiks izveidotas jaunas sacensības, tās parādīsies šeit.</p>
            </div>
        @endforelse
    </section>
</div>
@endsection
