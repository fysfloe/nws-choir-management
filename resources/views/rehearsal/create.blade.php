@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Create a new rehearsal') }}</h2>
    </header>

    {{ Form::model(new App\Rehearsal(), ['route' => 'rehearsal.store']) }}
        @include('rehearsal._form')
    {{ Form::close() }}

@endsection
