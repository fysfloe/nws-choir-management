@extends('layouts.modal')

@section('title')
    @if ($user->id === Auth::user()->id)
        {{ __('Set your role') }}
    @else
        {{ __('Set role for') }}: <span class="text-muted">{{ $user->firstname }} {{ $user->surname }}</span>
    @endif
@endsection

@section('body')
    {{ Form::open(['route' => 'role.set']) }}
        <input type="hidden" name="user" value="{{ $user->id }}">

        <div class="form-group">
            {{ Form::label('role', __('Role'), ['class' => 'control-label']) }}
            {{ Form::select('role', $roles, $role ? $role->id : null, ['class' => 'form-control']) }}
        </div>
    {{ Form::close() }}
@endsection

@section('save')
    {{ __('Set Role') }}
@endsection

@section('close')
    {{ __('Cancel') }}
@endsection
