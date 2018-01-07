@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Create a new semester') }}</h2>
    </header>

    {{ Form::model(new App\Semester(), ['route' => 'semesters.store']) }}
        @include('semester._form')
    {{ Form::close() }}

@endsection
