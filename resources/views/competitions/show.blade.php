@extends('layouts.app')

@section('title', $competition->name . ' - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competition-show.css') }}">
@endpush

@section('content')
@php
    $statusNames = [
        'planned' => 'Plānotas',
        'active' => 'Notiek',
        'finished' => 'Pabeigtas',
        'cancelled' => 'Atceltas',
    ];
    $participantsCount = $competition->users->count();
    $percent = $competition->max_players
        ? min(100, round(
            ($participantsCount / $competition->max_players) * 100
        ))
        : 0;
    $isParticipant = auth()->check()
        && $competition->users->contains('id', auth()->id());
    $isCreator = auth()->check()
        && $competition->user_id === auth()->id();
    $courseHoles = $competition->course?->courseHoles
        ?->sortBy('hole_number')
        ?->values() ?? collect();
    $coursePar = $courseHoles->sum('par');
@endphp
<div class="competition-page">
    @if(session('success'))
        <div class="notice notice-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="notice notice-error">
            {{ session('error') }}
        </div>
    @endif
    <!-- SACENSĪBU VIRSRaksts -->
    <div class="competition-hero">
        <div>
            <span class="page-label">DISKU GOLFA SACENSĪBAS</span>
            <h1>{{ $competition->name }}</h1>
            <span class="competition-status status-{{ $competition->status }}">
                {{ $statusNames[$competition->status] ?? 'Nezināms statuss' }}
            </span>
            @if($competition->description)
                <div class="competition-description-card">
                    <h3>Apraksts</h3>
                    <p>{{ trim($competition->description) }}</p>
                </div>
            @endif
        </div>
        @if($isParticipant && $competition->canEnterResults())
            <a
                href="{{ route('competitions.scorecard', $competition) }}"
                class="discapp-button scorecard-top-button"
            >
                {{ $competition->status === 'finished'
                    ? 'Skatīt rezultātu'
                    : 'Ievadīt rezultātu' }}
            </a>
        @endif
    </div>
    <!-- SACENSĪBU NORISES LAIKS -->
    <section class="competition-schedule">
        <h2>Sacensību norises laiks</h2>
        <div class="schedule-times">
            <div class="schedule-time">
                <span>🕒 Sākums</span>
                <strong>{{ $competition->starts_at?->format('d.m.Y H:i') ?? 'Nav norādīts' }}</strong>
            </div>
            <div class="schedule-time">
                <span>🏁 Beigas</span>
                <strong>{{ $competition->ends_at?->format('d.m.Y H:i') ?? 'Nav norādīts' }}</strong>
            </div>
        </div>
        @if($competition->canEnterResults())
            <div class="schedule-status schedule-active">Rezultātu ievade ir atvērta</div>
        @elseif($competition->ends_at && now()->gte($competition->ends_at))
            <div class="schedule-status schedule-ended">Sacensību laiks ir beidzies</div>
        @else
            <div class="schedule-status schedule-upcoming">Rezultātu ievade vēl nav pieejama</div>
        @endif
    </section>
    <!-- SACENSĪBU INFORMĀCIJA -->
    <section class="discapp-card">
        <div class="section-header">
            <div>
                <span class="section-label">INFORMĀCIJA</span>
                <h2>Par sacensībām</h2>
            </div>
        </div>
        <div class="meta-grid">
            <div class="meta-card">
                <span class="meta-label">📅 Datums</span>
                <strong>
                    {{ $competition->date?->format('d.m.Y') ?? '-' }}
                </strong>
            </div>
            <div class="meta-card">
                <span class="meta-label">📍 Trase</span>
                <strong>
                    {{ $competition->course?->name ?? 'Nav norādīta' }}
                </strong>
            </div>
            <div class="meta-card">
                <span class="meta-label">👥 Dalībnieki</span>
                <strong>{{ $participantsCount }}</strong>
            </div>
            <div class="meta-card">
                <span class="meta-label">🏆 Maksimums</span>
                <strong>
                    {{ $competition->max_players ?? 'Neierobežots' }}
                </strong>
            </div>
        </div>
        @if($competition->max_players)
            <div class="capacity-section">
                <div class="capacity-top">
                    <span>Vietu aizpildījums</span>
                    <strong>{{ $percent }}%</strong>
                </div>
                <div class="progress-bar-container">
                    <div
                        class="progress-bar"
                        style="width: {{ $percent }}%;"
                    ></div>
                </div>
            </div>
        @endif
    </section>
    <!-- DALĪBNIEKI -->
    <section class="discapp-card">
        <div class="section-header">
            <div>
                <span class="section-label">PIETEIKTIE SPĒLĒTĀJI</span>
                <h2>Dalībnieki</h2>
            </div>
            <span class="section-count">
                {{ $participantsCount }}
            </span>
        </div>
        @if($participantsCount > 0)
            @foreach(
                $competition->users->groupBy(
                    fn($player) => $player->pivot->division ?? 'Bez divīzijas'
                ) as $division => $divisionPlayers
            )
                <div class="division-group">
                    <div class="division-header">
                        <h3>{{ $division }}</h3>
                        <span class="division-count">
                            {{ $divisionPlayers->count() }}
                            spēlētāji
                        </span>
                    </div>
                    <div class="participants-grid">
                        @foreach($divisionPlayers as $player)
                            <div class="participant-card">
                                <div class="participant-avatar">
                                    {{ mb_strtoupper(
                                        mb_substr($player->name ?? '?', 0, 1)
                                    ) }}
                                </div>
                                <div class="participant-info">
                                    <strong>
                                        {{ $player->name ?? 'Nezināms spēlētājs' }}
                                    </strong>
                                    <span>
                                        {{ $player->pivot->division ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <h3>Vēl nav dalībnieku</h3>
                <p>Šobrīd šīm sacensībām neviens nav pieteicies.</p>
            </div>
        @endif
    </section>
    <!-- MANS REZULTĀTS -->
    @if($isParticipant)
        @php
            $myHoleResults = $competition->holeResults
                ->where('user_id', auth()->id());
            $totalScore = $myHoleResults->sum('score');
            $holesPlayed = $myHoleResults->count();
            $playedPar = 0;
            foreach ($myHoleResults as $holeResult) {
                $hole = $courseHoles->firstWhere(
                    'id',
                    $holeResult->course_hole_id
                );
                if ($hole) {
                    $playedPar += $hole->par;
                }
            }
            $scoreToPar = $totalScore - $playedPar;
            $totalHoles = $courseHoles->count();
        @endphp
        <section class="discapp-card">
            <div class="section-header">
                <div>
                    <span class="section-label">MANS REZULTĀTS</span>
                    <h2>Rezultātu ievade</h2>
                </div>
            </div>
            <div class="my-score-summary">
                <div class="score-summary-card">
                    <span>Izspēlēti grozi</span>
                    <strong>{{ $holesPlayed }}/{{ $totalHoles }}</strong>
                </div>
                <div class="score-summary-card">
                    <span>Metieni</span>
                    <strong>{{ $totalScore }}</strong>
                </div>
                <div class="score-summary-card">
                    <span>Pret PAR</span>
                    <strong>
                        @if($holesPlayed === 0)
                            -
                        @elseif($scoreToPar > 0)
                            +{{ $scoreToPar }}
                        @elseif($scoreToPar < 0)
                            {{ $scoreToPar }}
                        @else
                            E
                        @endif
                    </strong>
                </div>
            </div>
            <div style="margin-top: 24px;">
                @if($competition->canEnterResults())
                    <a
                        href="{{ route('competitions.scorecard', $competition) }}"
                        class="discapp-button"
                    >
                        Ievadīt / labot rezultātu →
                    </a>
                @else
                    <span class="result-not-entered">
                        Rezultātu ievade pašlaik nav pieejama.
                    </span>
                @endif
            </div>
        </section>
    @endif
    <!-- REZULTĀTU TABULA -->
    <section class="discapp-card">
        <div class="section-header">
            <div>
                <span class="section-label">SACENSĪBU REZULTĀTI</span>
                <h2>Rezultātu tabula</h2>
            </div>
            <span class="section-count">
                {{ $players->count() }}
            </span>
        </div>
        @if($players->count() === 0)
            <div class="empty-state">
                <h3>Rezultātu vēl nav</h3>
                <p>Šajās sacensībās vēl nav pieteicies neviens spēlētājs.</p>
            </div>
        @else
            @php
                $previousRelative = null;
                $previousPosition = 0;
            @endphp
            <div class="results-table-wrapper">
                <table class="metrix-table">
                    <thead>
                        <tr>
                            <th class="col-position">No</th>
                            <th class="col-name">Spēlētājs</th>
                            <th class="col-relative">+/-</th>
                            @foreach($courseHoles as $hole)
                                <th class="hole-heading">
                                    {{ $hole->hole_number }}
                                </th>
                            @endforeach
                            <th class="col-relative">+/-</th>
                            <th class="col-sum">Sum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="par-row">
                            <td></td>
                            <td class="par-title">PAR</td>
                            <td></td>
                            @foreach($courseHoles as $hole)
                                <td>{{ $hole->par }}</td>
                            @endforeach
                            <td></td>
                            <td>{{ $coursePar }}</td>
                        </tr>
                        @foreach($players as $index => $player)
                            @php
                                $playerUser = $player['user'] ?? null;
                                $playerResults = $playerUser
                                    ? $competition->holeResults->where(
                                        'user_id',
                                        $playerUser->id
                                    )
                                    : collect();
                                $played = $playerResults->count();
                                $playerTotal = $playerResults->sum('score');
                                $playerPlayedPar = 0;
                                foreach ($playerResults as $result) {
                                    $resultHole = $courseHoles->firstWhere(
                                        'id',
                                        $result->course_hole_id
                                    );
                                    if ($resultHole) {
                                        $playerPlayedPar += $resultHole->par;
                                    }
                                }
                                $playerRelative = $playerTotal - $playerPlayedPar;
                                if ($played === 0) {
                                    $displayPosition = '-';
                                } else {
                                    if (
                                        $previousRelative !== null
                                        && $previousRelative === $playerRelative
                                    ) {
                                        $displayPosition = $previousPosition;
                                    } else {
                                        $displayPosition = $index + 1;
                                        $previousPosition = $displayPosition;
                                    }
                                    $previousRelative = $playerRelative;
                                }
                            @endphp
                            <tr>
                                <td class="position-cell">
                                    {{ $displayPosition }}
                                </td>
                                <td class="player-name-cell">
                                    <strong>
                                        {{ $playerUser?->name ?? 'Nezināms spēlētājs' }}
                                    </strong>
                                    @if($player['division'] ?? null)
                                        <span class="player-division">
                                            {{ $player['division'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="relative-cell">
                                    @if($played === 0)
                                        -
                                    @elseif($playerRelative > 0)
                                        +{{ $playerRelative }}
                                    @elseif($playerRelative < 0)
                                        {{ $playerRelative }}
                                    @else
                                        E
                                    @endif
                                </td>
                                @foreach($courseHoles as $hole)
                                    @php
                                        $holeResult = $playerResults->firstWhere(
                                            'course_hole_id',
                                            $hole->id
                                        );
                                        $holeScore = $holeResult?->score;
                                        $difference = $holeScore !== null
                                            ? $holeScore - $hole->par
                                            : null;
                                        $scoreClass = '';
                                        if ($difference !== null) {
                                            if ($difference <= -2) {
                                                $scoreClass = 'hole-double-under';
                                            } elseif ($difference === -1) {
                                                $scoreClass = 'hole-under';
                                            } elseif ($difference === 0) {
                                                $scoreClass = 'hole-par';
                                            } elseif ($difference === 1) {
                                                $scoreClass = 'hole-over';
                                            } else {
                                                $scoreClass = 'hole-double-over';
                                            }
                                        }
                                    @endphp
                                    <td class="hole-score {{ $scoreClass }}">
                                        @if($holeScore !== null)
                                            {{ $holeScore }}
                                        @else
                                            <span class="no-score">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="relative-cell">
                                    @if($played === 0)
                                        -
                                    @elseif($playerRelative > 0)
                                        +{{ $playerRelative }}
                                    @elseif($playerRelative < 0)
                                        {{ $playerRelative }}
                                    @else
                                        E
                                    @endif
                                </td>
                                <td class="sum-cell">
                                    {{ $played > 0 ? $playerTotal : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
    <!-- SACENSĪBU DARBĪBAS -->
    <section class="discapp-card">
        <div class="section-header">
            <div>
                <span class="section-label">DARBĪBAS</span>
                <h2>Sacensību pārvaldība</h2>
            </div>
        </div>
        <div class="competition-actions">
            @auth
                @if($isCreator)
                    <span class="creator-status">
                        Tu esi šo sacensību veidotājs.
                    </span>
                @endif

                @if($isParticipant)
                    @if($competition->status === 'planned')
                        <form
                            method="POST"
                            action="{{ route('competitions.leave', $competition) }}"
                            onsubmit="return confirm('Vai tiešām vēlies izstāties no sacensībām?');"
                        >
                            @csrf
                            <button type="submit" class="discapp-danger-button">
                                Izstāties no sacensībām
                            </button>
                        </form>
                    @endif
                @elseif(
                    auth()->user()->role !== 'admin'
                    && $competition->status === 'planned'
                    && (
                        !$competition->max_players
                        || $participantsCount < $competition->max_players
                    )
                )
                    <a
                        href="{{ route('competitions.join', $competition) }}"
                        class="discapp-button"
                    >
                        Pievienoties sacensībām →
                    </a>
                @endif
            @endauth

            <a
                href="{{ route('competitions.index') }}"
                class="discapp-secondary-button"
            >
                Atpakaļ uz sacensībām
            </a>
        </div>
        @auth
            @if($isCreator)
                <div class="competition-admin-actions">
                    <a
                        href="{{ route('competitions.edit', $competition) }}"
                        class="discapp-button"
                    >
                        Rediģēt sacensības
                    </a>
                    <form
                        method="POST"
                        action="{{ route('competitions.destroy', $competition) }}"
                        onsubmit="return confirm('Vai tiešām vēlies dzēst šīs sacensības?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="discapp-danger-button"
                        >
                            Dzēst sacensības
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </section>
</div>
@endsection
