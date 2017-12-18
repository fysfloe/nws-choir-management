@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Set Voice') }}</h2>
    </header>

    {{ Form::open(['route' => 'voice.set']) }}
        <input type="hidden" name="user" value="{{ Auth::user()->id }}">

        <div class="form-group">
            {{ Form::label('voice', trans('Voice'), ['class' => 'control-label']) }}
            {{ Form::select('voice', $voices, $voice->id, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::button(trans('Set Voice'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
        </div>
    {{ Form::close() }}

@endsection
