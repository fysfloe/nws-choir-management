@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Edit Semester') }}</h2>
    </header>

    {{ Form::model($semester, ['route' => ['semesters.update', $semester->id], 'method' => 'PUT']) }}
        @include('semester._form')
    {{ Form::close() }}

@endsection
