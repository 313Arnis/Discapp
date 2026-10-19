@extends('layouts.app')

@section('title', $competition->name . ' - Scorecard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competition-scorecard.css') }}">
@endpush

@section('content')
@php
    $currentIndex = $courseHoles->search(
        fn ($hole) => $currentHole &&
            $hole->id === $currentHole->id
    );

    $previousHole = $currentIndex !== false && $currentIndex > 0
        ? $courseHoles[$currentIndex - 1]
        : null;

    $nextHole = $currentIndex !== false &&
        $currentIndex < $courseHoles->count() - 1
        ? $courseHoles[$currentIndex + 1]
        : null;

    $playedCount = $results->count();
    $totalScore = $results->sum('score');
    $playedPar = 0;

    foreach ($results as $courseHoleId => $result) {
        $playedHole = $courseHoles->firstWhere('id', $courseHoleId);

        if ($playedHole) {
            $playedPar += $playedHole->par;
        }
    }

    $relativeToPar = $totalScore - $playedPar;
    $allHolesPlayed = $courseHoles->count() > 0 &&
        $playedCount >= $courseHoles->count();

    $canEdit = $competition->canEnterResults();
@endphp

<div class="scorecard-page">

    {{-- PAZIŅOJUMI --}}
    @if($errors->any())
        <div class="scorecard-error">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="scorecard-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- GALVENE --}}
    <div class="scorecard-header">
        <h1>{{ $competition->name }}</h1>

        <div class="competition-button">
            Sacensības
        </div>
    </div>

    {{-- SACENSĪBU LAIKS --}}
    <div class="scorecard-schedule">
        <span>
            Sākums:
            <strong>
                {{ $competition->starts_at?->format('d.m.Y H:i') ?? '-' }}
            </strong>
        </span>

        <span>
            Beigas:
            <strong>
                {{ $competition->ends_at?->format('d.m.Y H:i') ?? '-' }}
            </strong>
        </span>
    </div>

    {{-- GROZU INDIKATORI --}}
    <div class="hole-indicators">
        @foreach($courseHoles as $hole)
            @php
                $completed = $results->has($hole->id);
                $active = $currentHole &&
                    $hole->id === $currentHole->id;
            @endphp

            <a
                href="{{ route('competitions.scorecard', [
                    'competition' => $competition,
                    'hole' => $hole->hole_number,
                ]) }}"
                class="hole-indicator {{ $active ? 'active' : ($completed ? 'completed' : '') }}"
                title="Grozs {{ $hole->hole_number }}"
                aria-label="Grozs {{ $hole->hole_number }}"
                @if($active) aria-current="step" @endif
            ></a>
        @endforeach
    </div>

    {{-- GROZA INFORMĀCIJA --}}
    @if($currentHole)
        <div class="hole-info">
            <div class="hole-info-title">
                Grozs {{ $currentHole->hole_number }}
                | PAR {{ $currentHole->par }}
            </div>

            <div class="hole-info-subtitle">
                {{ $competition->course->name }}
                ·
                {{ $competition->name }}
            </div>
        </div>
    @endif

    {{-- REZULTĀTA IEVADES FORMA --}}
    @if($currentHole)
        @php
            $result = $results->get($currentHole->id);
            $relative = $result
                ? $result->score - $currentHole->par
                : 0;
        @endphp

        <div class="score-card">
            <form
                id="scoreForm"
                method="POST"
                action="{{ route('competitions.scorecard.store', $competition) }}"
                class="competition-score-form"
            >
                @csrf

                <input
                    type="hidden"
                    name="course_hole_id"
                    value="{{ $currentHole->id }}"
                >

                <div class="player-row">
                    <div class="player-position">1.</div>

                    <div class="player-name">
                        {{ auth()->user()->name }}

                        @if(auth()->user()->surname)
                            {{ auth()->user()->surname }}
                        @endif
                    </div>

                    <div class="penalty">PEN</div>

                    <input
                        type="number"
                        id="scoreInput"
                        name="score"
                        class="score-input"
                        min="1"
                        max="100"
                        value="{{ old('score', $result?->score) }}"
                        placeholder="Rezultāts"
                        inputmode="numeric"
                        @if($canEdit)
                            required
                            autofocus
                        @else
                            disabled
                        @endif
                    >

                    <div
                        class="relative-score"
                        id="relativeScore"
                    >
                        @if(!$result)
                            <span class="even-par">-</span>
                        @elseif($relative < 0)
                            <span class="under-par">
                                {{ $relative }}
                            </span>
                        @elseif($relative > 0)
                            <span class="over-par">
                                +{{ $relative }}
                            </span>
                        @else
                            <span class="even-par">E</span>
                        @endif
                    </div>
                </div>

                @if($canEdit)
                    <div class="autosave-info">
                        Ieraksti rezultātu — tas saglabāsies automātiski.
                    </div>
                @else
                    <div class="autosave-info">
                        Rezultātu ievade pašlaik nav pieejama.
                    </div>
                @endif
            </form>
        </div>
    @endif

    {{-- NAVIGĀCIJA --}}
    <div class="score-navigation">
        @if($previousHole)
            <a
                href="{{ route('competitions.scorecard', [
                    'competition' => $competition,
                    'hole' => $previousHole->hole_number,
                ]) }}"
                class="nav-hole"
            >
                ← Iepriekšējais
            </a>
        @else
            <span class="nav-hole disabled">
                ← Iepriekšējais
            </span>
        @endif

        <a
            href="{{ route('competitions.show', $competition) }}"
            class="nav-hole"
        >
            Sacensības
        </a>

        @if($nextHole)
            <a
                href="{{ route('competitions.scorecard', [
                    'competition' => $competition,
                    'hole' => $nextHole->hole_number,
                ]) }}"
                class="nav-hole"
            >
                Nākamais →
            </a>
        @else
            <span class="nav-hole disabled">
                Nākamais →
            </span>
        @endif
    </div>

    {{-- REZULTĀTU KOPSAVILKUMS --}}
    <div class="summary">
        <div class="summary-title">
            Mans sacensību rezultāts
        </div>

        <div class="summary-row">
            <span>Sacensības</span>
            <strong>{{ $competition->name }}</strong>
        </div>

        <div class="summary-row">
            <span>Trase</span>
            <strong>{{ $competition->course->name }}</strong>
        </div>

        <div class="summary-row">
            <span>Izspēlēti grozi</span>
            <strong>
                {{ $playedCount }}/{{ $courseHoles->count() }}
            </strong>
        </div>

        <div class="summary-row">
            <span>Metieni</span>
            <strong>{{ $totalScore }}</strong>
        </div>

        <div class="summary-row">
            <span>Pret PAR</span>
            <strong>
                @if($playedCount === 0)
                    -
                @elseif($relativeToPar > 0)
                    +{{ $relativeToPar }}
                @elseif($relativeToPar < 0)
                    {{ $relativeToPar }}
                @else
                    E
                @endif
            </strong>
        </div>
    </div>

    {{-- STATUSA PAZIŅOJUMI --}}
    @if($competition->status === 'finished')
        <div class="finished-message">
            Sacensības ir pabeigtas. Rezultātus vairs nevar mainīt.
        </div>
    @elseif($competition->status === 'cancelled')
        <div class="finished-message">
            Sacensības ir atceltas.
        </div>
    @elseif(!$canEdit)
        <div class="finished-message">
            Rezultātu ievade nav pieejama ārpus sacensību norises laika.
        </div>
    @elseif($allHolesPlayed)
        <div class="finished-message">
            Visi grozi ir aizpildīti.
            Gala rezultāts ir saglabāts.
        </div>
    @endif

</div>
@endsection

@push('scripts')
@if($currentHole && $canEdit)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scoreForm = document.getElementById('scoreForm');
    const scoreInput = document.getElementById('scoreInput');
    const relativeScore = document.getElementById('relativeScore');

    if (!scoreForm || !scoreInput || !relativeScore) {
        return;
    }

    const par = {{ (int) $currentHole->par }};
    let saveTimer = null;
    let isSubmitting = false;

    function updateRelativeScore() {
        const score = Number.parseInt(scoreInput.value, 10);

        if (!Number.isInteger(score) || score < 1 || score > 100) {
            relativeScore.innerHTML =
                '<span class="even-par">-</span>';
            return;
        }

        const relative = score - par;

        if (relative < 0) {
            relativeScore.innerHTML =
                '<span class="under-par">' + relative + '</span>';
        } else if (relative > 0) {
            relativeScore.innerHTML =
                '<span class="over-par">+' + relative + '</span>';
        } else {
            relativeScore.innerHTML =
                '<span class="even-par">E</span>';
        }
    }

    function submitScore() {
        if (isSubmitting) {
            return;
        }

        const score = Number.parseInt(scoreInput.value, 10);

        if (
            !Number.isInteger(score) ||
            score < 1 ||
            score > 100
        ) {
            return;
        }

        isSubmitting = true;
        scoreInput.classList.add('saving');
        scoreForm.submit();
    }

    function scheduleSave() {
        clearTimeout(saveTimer);

        const score = Number.parseInt(scoreInput.value, 10);

        if (
            !Number.isInteger(score) ||
            score < 1 ||
            score > 100
        ) {
            return;
        }

        saveTimer = setTimeout(submitScore, 650);
    }

    scoreInput.addEventListener('input', function () {
        updateRelativeScore();
        scheduleSave();
    });

    scoreInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(saveTimer);
            submitScore();
        }
    });

    scoreInput.focus();
    scoreInput.select();
});
</script>
@endif
@endpush
