@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2><span class="light">{{ trans('Edit') }}:</span> {{ $concert->title }}</h2>
    </header>

    {{ Form::model($concert, ['route' => 'concert.update']) }}
        @include('concert._form')
    {{ Form::close() }}

@endsection
