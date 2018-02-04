@extends('layouts.modal')

@section('title')
    {{ __('Set voice for') }}: {{ count($users) }} {{ __('users') }}
@endsection

@section('body')
    {{ Form::open(['route' => ['voice.setMulti']]) }}
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
