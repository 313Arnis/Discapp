
@extends('layouts.app')

@section('title', 'Izveidot sacensības - Discapp')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competition-form.css') }}">
@endpush

@section('content')
<div class="competition-form-page">
    <h1>Izveidot sacensības</h1>

    @include('competitions._form', ['competition' => null])
</div>
@endsection
