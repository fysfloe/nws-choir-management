@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2><span class="light">{{ trans('Edit') }}:</span> {{ $voice->name }}</h2>
    </header>

    {{ Form::model($voice, ['method' => 'PATCH', 'route' => ['voices.update', $voice]]) }}
        @include('voice._form')
    {{ Form::close() }}

@endsection
