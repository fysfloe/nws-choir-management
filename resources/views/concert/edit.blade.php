@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2><span class="light">{{ __('Edit') }}:</span> {{ $concert->title }}</h2>
    </header>

    {{ Form::model($concert, ['route' => ['concert.update', $concert->id], 'method' => 'PUT']) }}
        @include('concert._form')
    {{ Form::close() }}

@endsection
