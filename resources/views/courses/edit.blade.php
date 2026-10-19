
@extends('layouts.app')

@section('title', $course->name . ' - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-courses.css') }}">
@endpush

@section('content')
<main class="courses-page">

    <div class="courses-header">
        <div>
            <span class="courses-eyebrow">TRASES REDIĢĒŠANA</span>
            <h1>{{ $course->name }}</h1>
            <p>{{ $course->holes }} grozi · Pārvaldi trases PAR vērtības.</p>
        </div>

        <a href="{{ route('courses.index') }}" class="courses-btn-secondary">
            ← Atpakaļ uz trasēm
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

    <section class="courses-panel">
        <div class="courses-panel-heading">
            <span class="courses-panel-icon">⛳</span>
            <div>
                <h2>Trases PAR</h2>
                <p>Norādi katra groza PAR vērtību.</p>
            </div>
        </div>

        <form
            action="{{ route('courses.update', $course) }}"
            method="POST"
            class="courses-form"
        >
            @csrf
            @method('PUT')

            <div class="courses-holes-grid">
                @foreach($course->courseHoles->sortBy('hole_number') as $hole)
                    <div class="courses-hole-card">
                        <label for="par_{{ $hole->hole_number }}">
                            <span class="courses-hole-number">
                                Grozs {{ $hole->hole_number }}
                            </span>
                            <span class="courses-hole-caption">
                                PAR vērtība
                            </span>
                        </label>

                        <input
                            type="number"
                            id="par_{{ $hole->hole_number }}"
                            name="par[{{ $hole->hole_number }}]"
                            value="{{ old('par.' . $hole->hole_number, $hole->par) }}"
                            min="2"
                            max="6"
                            required
                        >
                    </div>
                @endforeach
            </div>

            <div class="courses-form-actions">
                <a href="{{ route('courses.index') }}" class="courses-btn-secondary">
                    Atcelt
                </a>

                <button type="submit" class="courses-btn-primary">
                    Saglabāt PAR
                </button>
            </div>
        </form>
    </section>

</main>
@endsection
