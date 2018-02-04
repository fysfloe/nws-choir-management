@extends('layouts.modal')

@section('title')
    {{ __('Set role for') }}: {{ count($users) }} {{ __('users') }}
@endsection

@section('body')
    {{ Form::open(['route' => ['role.setMulti']]) }}
        <div class="form-group">
            {{ Form::label('role', __('Role'), ['class' => 'control-label']) }}
            {{ Form::select('role', $roles, null, ['class' => 'form-control']) }}

            <input type="hidden" name="users" value='{{ json_encode($users) }}' />
        </div>
    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Set Role') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
