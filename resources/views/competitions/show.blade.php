<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $competition->name }} - Discapp</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            background: #f8f8f6;
            color: #222;
            font-family: Arial, sans-serif;
        }
        /* NAVIGĀCIJA */
        body nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            margin: 0 12px;
            padding: 20px 36px;
            background: #f8f8f6;
            box-shadow: 0 3px 9px rgba(0, 0, 0, .08);
        }
        body nav h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }
        body nav h2 a {
            color: #ed6b26;
            text-decoration: none;
        }
        .discapp-nav-links,
        .discapp-nav-auth {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 28px;
        }
        .discapp-nav-links a {
            color: #222;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
        }
        .discapp-nav-links a:hover {
            color: #ed6b26;
        }
        .discapp-nav-auth span {
            color: #666;
        }
        body nav .discapp-nav-auth form {
            width: auto;
            max-width: none;
            margin: 0;
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        body nav .logout-button {
            width: auto;
            padding: 13px 22px;
            background: #ed6b26;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
        }
        /* LAPAS SATURS */
        main.competition-page {
            width: min(100% - 40px, 1200px);
            max-width: 1200px;
            margin: 55px auto 90px;
            padding: 0;
        }
        .page-label,
        .section-label {
            display: inline-block;
            color: #e76621;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .page-label {
            padding: 10px 16px;
            background: #fff0e8;
            border-radius: 30px;
            margin-bottom: 18px;
        }
        .competition-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 30px;
        }
        .competition-hero h1 {
            margin: 0 0 15px;
            font-size: clamp(32px, 5vw, 48px);
            line-height: 1.15;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }
        .competition-description {
            margin: 18px 0 0;
            max-width: 750px;
            color: #777;
            font-size: 16px;
            line-height: 1.7;
            white-space: pre-line;
        }
        .competition-description-card {
            margin-top: 24px;
            padding: 25px 30px;
            max-width: 750px;
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .04);
        }
        .competition-description-card h3 {
            margin: 0 0 12px;
            color: #ed6b26;
            font-size: 18px;
            font-weight: 700;
        }
        .competition-description-card p {
            margin: 0;
            color: #666;
            font-size: 16px;
            line-height: 1.7;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }
        .competition-status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            background: #fff0e8;
            color: #e76621;
        }
        .status-active {
            background: #e5f6e9;
            color: #238349;
        }
        .status-finished {
            background: #eaeaea;
            color: #555;
        }
        .status-cancelled {
            background: #ffebeb;
            color: #c44040;
        }
        .discapp-card {
            padding: 32px;
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 26px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, .04);
            margin-bottom: 25px;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 24px;
        }
        .section-header h2 {
            margin: 8px 0 0;
            font-size: 25px;
            text-align: left;
        }
        .section-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 12px;
            background: #fff0e8;
            color: #ed6b26;
            font-weight: 800;
        }
        /* INFORMĀCIJAS KARTĪTES */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }
        .meta-card {
            padding: 22px;
            background: #fafafa;
            border: 1px solid #ededed;
            border-radius: 16px;
        }
        .meta-label {
            display: block;
            margin-bottom: 12px;
            color: #888;
            font-size: 13px;
        }
        .meta-card strong {
            display: block;
            font-size: 20px;
            overflow-wrap: anywhere;
        }
        /* VIETU AIZPILDĪJUMS */
        .capacity-section {
            margin-top: 26px;
        }
        .capacity-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #666;
            font-size: 14px;
        }
        .capacity-top strong {
            color: #ed6b26;
        }
        .progress-bar-container {
            width: 100%;
            height: 12px;
            overflow: hidden;
            background: #f0f0f0;
            border-radius: 20px;
        }
        .progress-bar {
            height: 100%;
            background: #ed6b26;
            border-radius: 20px;
        }
        /* DALĪBNIEKI */
        .division-group {
            margin-top: 25px;
        }
        .division-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }
        .division-header h3 {
            margin: 0;
            font-size: 19px;
        }
        .division-count {
            color: #888;
            font-size: 13px;
        }
        .participants-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .participant-card {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px;
            border: 1px solid #ededed;
            border-radius: 15px;
            background: #fafafa;
        }
        .participant-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: #fff0e8;
            color: #ed6b26;
            font-size: 19px;
            font-weight: 800;
        }
        .participant-info {
            min-width: 0;
        }
        .participant-info strong {
            display: block;
            font-size: 14px;
            overflow-wrap: anywhere;
        }
        .participant-info span {
            display: block;
            margin-top: 5px;
            color: #888;
            font-size: 12px;
        }
        .empty-state {
            padding: 35px 20px;
            text-align: center;
            background: #fafafa;
            border-radius: 16px;
            color: #777;
        }
        .empty-state h3 {
            color: #222;
        }
        /* REZULTĀTU KOPSAVILKUMS */
        .my-score-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }
        .score-summary-card {
            padding: 23px;
            border-radius: 16px;
            background: #fafafa;
            border: 1px solid #ededed;
        }
        .score-summary-card span {
            display: block;
            margin-bottom: 12px;
            color: #888;
            font-size: 13px;
        }
        .score-summary-card strong {
            font-size: 28px;
            color: #ed6b26;
        }
        /* REZULTĀTU TABULA */
        .results-table-wrapper {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e7e7e7;
            border-radius: 15px;
        }
        .metrix-table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
            background: #fff;
        }
        .metrix-table th,
        .metrix-table td {
            height: 43px;
            padding: 9px 7px;
            border-right: 1px solid #e8e8e8;
            border-bottom: 1px solid #e8e8e8;
            text-align: center;
            font-size: 13px;
            white-space: nowrap;
        }
        .metrix-table th:last-child,
        .metrix-table td:last-child {
            border-right: none;
        }
        .metrix-table thead th {
            background: #fff0e8;
            color: #8e451f;
            font-weight: 800;
        }
        .col-position {
            min-width: 50px;
        }
        .col-name,
        .player-name-cell {
            min-width: 180px;
            text-align: left !important;
            padding-left: 14px !important;
        }
        .col-relative {
            min-width: 55px;
        }
        .hole-heading,
        .hole-score {
            min-width: 42px;
        }
        .col-sum,
        .sum-cell {
            min-width: 65px;
        }
        .par-row td {
            background: #f8f8f8;
            font-weight: 700;
        }
        .par-title {
            text-align: right !important;
        }
        .player-name-cell strong {
            display: block;
            color: #222;
        }
        .player-division {
            display: block;
            margin-top: 4px;
            color: #999;
            font-size: 11px;
        }
        .position-cell,
        .relative-cell,
        .sum-cell {
            font-weight: 800;
        }
        .hole-score {
            font-weight: 700;
        }
        .hole-double-under {
            background: #83d96b;
        }
        .hole-under {
            background: #b7eaa5;
        }
        .hole-par {
            background: #fff;
        }
        .hole-over {
            background: #f8ddd4;
        }
        .hole-double-over {
            background: #f3ae9c;
        }
        .no-score {
            color: #bbb;
        }
        .metrix-table tbody tr:not(.par-row):hover td {
            filter: brightness(.97);
        }
        /* POGAS */
        .discapp-button,
        .discapp-secondary-button,
        .discapp-danger-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            min-height: 46px;
            padding: 13px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }
        .discapp-button {
            border: 1px solid #ed6b26;
            background: #ed6b26;
            color: white !important;
        }
        .discapp-button:hover {
            background: #d95b19;
            color: white !important;
            text-decoration: none;
        }
        .discapp-secondary-button {
            background: white;
            border: 1px solid #ddd;
            color: #222 !important;
        }
        .discapp-secondary-button:hover {
            background: #f4f4f4;
            text-decoration: none;
        }
        .discapp-danger-button {
            background: #fff;
            border: 1px solid #edc4c4;
            color: #c44040;
        }
        .discapp-danger-button:hover {
            background: #fff0f0;
        }
        .competition-actions,
        .competition-admin-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }
        .competition-actions form,
        .competition-admin-actions form {
            width: auto;
            max-width: none;
            margin: 0;
            padding: 0;
            border: none;
            background: transparent;
            box-shadow: none;
        }
        .creator-status {
            color: #777;
            font-size: 14px;
        }
        .result-not-entered {
            display: inline-block;
            padding: 12px 15px;
            background: #f5f5f5;
            border-radius: 10px;
            color: #777;
            font-size: 13px;
        }
        .scorecard-top-button {
            white-space: nowrap;
        }
        .notice {
            padding: 16px 20px;
            margin-bottom: 22px;
            border-radius: 12px;
            font-size: 14px;
        }
        .notice-success {
            background: #eaf7ed;
            color: #277a41;
        }
        .notice-error {
            background: #fff0f0;
            color: #b83c3c;
        }
        @media (max-width: 950px) {
            .meta-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .participants-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .discapp-nav-links {
                order: 3;
                width: 100%;
                gap: 18px;
            }
        }
        @media (max-width: 650px) {
            main.competition-page {
                width: min(100% - 24px, 1200px);
                margin-top: 30px;
            }
            body nav {
                padding: 18px;
            }
            .competition-hero {
                flex-direction: column;
            }
            .discapp-card {
                padding: 22px 17px;
                border-radius: 20px;
            }
            .meta-grid,
            .participants-grid {
                grid-template-columns: 1fr;
            }
            .my-score-summary {
                grid-template-columns: 1fr;
            }
            .competition-actions,
            .competition-admin-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .competition-actions form,
            .competition-admin-actions form,
            .competition-actions a,
            .competition-admin-actions a {
                width: 100%;
            }
            .competition-actions button,
            .competition-admin-actions button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<nav>
    <h2>
        <a href="{{ route('home') }}">Discapp</a>
    </h2>
    <div class="discapp-nav-links">
        <a href="{{ route('home') }}">Sākums</a>
        <a href="{{ route('competitions.index') }}">Sacensības</a>
        <a href="{{ route('players.index') }}">Spēlētāji</a>
        @auth
            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('practice.index') }}">Practice</a>
                <a href="{{ route('profile') }}">Mans profils</a>
            @else
                <a href="{{ route('admin') }}">Admin panelis</a>
            @endif
        @endauth
    </div>
    <div class="discapp-nav-auth">
        @auth
            <span>Sveiks, {{ auth()->user()->name }}!</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    Iziet
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="discapp-button">
                Pieslēgties
            </a>
        @endauth
    </div>
</nav>
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
<main class="competition-page">
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
        @if($isParticipant && in_array($competition->status, ['active', 'finished']))
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
                @if($competition->status === 'active')
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
                @elseif($isParticipant)
                    @if(!in_array($competition->status, ['finished', 'cancelled']))
                        <form
                            method="POST"
                            action="{{ route('competitions.leave', $competition) }}"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="discapp-danger-button"
                            >
                                Izstāties no sacensībām
                            </button>
                        </form>
                    @endif
                @elseif(
                    auth()->user()->role !== 'admin'
                    && $competition->isRegistrationOpen()
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
</main>
</body>
</html>
