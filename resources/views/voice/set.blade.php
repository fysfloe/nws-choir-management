@extends('layouts.modal')

@section('title')
    @if ($user->id === Auth::user()->id)
        {{ __('Set your voice') }}
    @else
        {{ __('Set voice for') }}: <span class="text-muted">{{ $user->firstname }} {{ $user->surname }}</span>
    @endif
@endsection

@section('body')
    {{ Form::open(['route' => ['voice.set', $user]]) }}
        <div class="form-group">
            {{ Form::label('voice', __('Voice'), ['class' => 'control-label']) }}
            {{ Form::select('voice', $voices, $user->voice ? $user->voice->id : null, ['class' => 'form-control']) }}
        </div>
    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Set Voice') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
