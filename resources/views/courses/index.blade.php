
@extends('layouts.app')

@section('title', 'Trases - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-courses.css') }}">
@endpush

@section('content')
<main class="courses-page">

    <div class="courses-header">
        <div>
            <span class="courses-eyebrow">DISCAPP ADMINISTRĀCIJA</span>
            <h1>Disku golfa trases</h1>
            <p>Pārvaldi trases, grozu PAR vērtības un reitinga parametrus.</p>
        </div>

        <a href="{{ route('courses.create') }}" class="courses-btn-primary">
            + Pievienot trasi
        </a>
    </div>

    @if(session('success'))
        <div class="courses-alert courses-alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="courses-alert courses-alert-error" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="courses-list">
        @forelse($courses as $course)
            <article class="courses-list-card">
                <div class="courses-list-top">
                    <span class="courses-list-icon">⛳</span>

                    <div>
                        <h2>{{ $course->name }}</h2>
                        <span class="courses-list-subtitle">
                            Disku golfa trase
                        </span>
                    </div>
                </div>

                <div class="courses-details-grid">
                    <div class="courses-detail">
                        <span>Grozu skaits</span>
                        <strong>{{ $course->holes }}</strong>
                    </div>

                    <div class="courses-detail">
                        <span>Kopējais PAR</span>
                        <strong>{{ $course->courseHoles()->sum('par') }}</strong>
                    </div>

                    <div class="courses-detail">
                        <span>1000 reitinga rezultāts</span>
                        <strong>{{ $course->rating_1000_score }}</strong>
                    </div>

                    <div class="courses-detail">
                        <span>Punkti par metienu</span>
                        <strong>{{ $course->rating_per_throw }}</strong>
                    </div>
                </div>

                <div class="courses-list-actions">
                    <a
                        href="{{ route('courses.edit', $course) }}"
                        class="courses-btn-primary"
                    >
                        Rediģēt PAR
                    </a>

                    <form
                        action="{{ route('courses.destroy', $course) }}"
                        method="POST"
                        onsubmit="return confirm('Vai tiešām vēlies dzēst šo trasi?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="courses-btn-danger">
                            Dzēst trasi
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="courses-empty">
                <span class="courses-empty-icon">⛳</span>
                <h2>Nav pievienotu trašu</h2>
                <p>Izveido pirmo disku golfa trasi, lai sāktu darbu.</p>

                <a href="{{ route('courses.create') }}" class="courses-btn-primary">
                    + Pievienot pirmo trasi
                </a>
            </div>
        @endforelse
    </div>

</main>
@endsection
