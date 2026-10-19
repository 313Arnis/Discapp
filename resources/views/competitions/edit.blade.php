
@extends('layouts.app')

@section('title', 'Rediģēt sacensības - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competition-form.css') }}">
@endpush

@section('content')
<div class="competition-form-page">
    <h1>Rediģēt sacensības</h1>

    @include('competitions._form', ['competition' => $competition])
</div>
@endsection
