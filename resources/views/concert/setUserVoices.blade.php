@extends('layouts.modal')

@section('title')
    {{ __('Set voice for') }}: {{ count($users) }} {{ __('users') }}
@endsection

@section('body')
    {{ Form::open(['route' => ['concert.setUserVoices', $concert]]) }}
        <small class="help-text text-muted">{{ __('You are setting the voices for the selected users for this concert. To set the primary voice, edit the users in the main users list.') }}</small>

        <div class="form-group">
            {{ Form::label('voice', __('Voice'), ['class' => 'control-label']) }}
            {{ Form::select('voice', $voices, null, ['class' => 'form-control']) }}

            <input type="hidden" name="users" value='{{ json_encode($users) }}' />
        </div>
    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Set Voice') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
