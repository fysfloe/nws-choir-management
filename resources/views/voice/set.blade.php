@extends('layouts.modal')

@section('title')
    @if ($user->id === Auth::user()->id)
        {{ trans('Set your voice') }}
    @else
        {{ trans('Set voice for') }}: <span class="text-muted">{{ $user->firstname }} {{ $user->surname }}</span>
    @endif
@endsection

@section('body')
    {{ Form::open(['route' => 'voice.set']) }}
        <input type="hidden" name="user" value="{{ $user->id }}">

        <div class="form-group">
            {{ Form::label('voice', trans('Voice'), ['class' => 'control-label']) }}
            {{ Form::select('voice', $voices, $voice ? $voice->id : null, ['class' => 'form-control']) }}
        </div>
    {{ Form::close() }}
@endsection

@section('save')
    {{ trans('Set Voice') }}
@endsection

@section('close')
    {{ trans('Cancel') }}
@endsection
