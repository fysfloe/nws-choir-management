@extends('layouts.modal')

@section('title')
    {{ __('Set voice for') }}: <span class="text-muted">{{ $user->firstname }} {{ $user->surname }}</span>
@endsection

@section('body')
    <div class="set-user-voice">
        <small class="help-text text-muted">{{ __('You are setting the voice for this user for this project. To set the primary voice, edit the user in the main users list.') }}</small>

        {{ Form::open(['route' => ['project.setUserVoice', $project, $user]]) }}
            <div class="form-group">
                {{ Form::label('voice', __('Voice'), ['class' => 'control-label']) }}
                {{ Form::select('voice', $voices, $voice_id, ['class' => 'form-control']) }}
            </div>
        {{ Form::close() }}
    </div>
@endsection

@section('save')
    {{ __('Set Voice') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
