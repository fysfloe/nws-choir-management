@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2><span class="light">{{ __('Edit') }}:</span> {{ date_format(date_create($rehearsal->date), 'd.m.Y') }}</h2>
    </header>

    {{ Form::model($rehearsal, ['route' => 'rehearsal.update']) }}
        @include('rehearsal._form')
    {{ Form::close() }}

@endsection
