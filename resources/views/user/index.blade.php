@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>Users</h2>
        @permission('manageUsers')
            <a class="btn btn-default btn-sm" href="{{ route('users.create') }}">
                {{ __('New User') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('users.export', app('request')->all()) }}">
                {{ __('Export') }}
            </a>
        @endpermission
    </header>

    @include('user._filters')

    @if(count($users) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-8">
                    {{ __('User') }}
                    <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'surname', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'surname' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'surname', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-2">
                    {{ __('Voice') }}
                    <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'voice' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('users.index', ['sort' => 'voice', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-2">{{ __('Role') }}</div>
            </header>

            <ul class="users">
                @foreach($users as $user)
                    <li class="row">
                        <div class="col-md-8">
                            {{ $user->firstname }} {{ $user->surname }}
                            <a href="{{ route('profile.edit', $user->id) }}" class="btn-link btn-sm">
                                <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit Profile') }}"></span>
                            </a>
                            {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to archive this user?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['users.destroy', $user]]) !!}
                                <button type="submit" class="btn-link">
                                    <span class="oi oi-box" data-toggle="tooltip" title="{{ __('Archive') }}"></span>
                                </button>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-2">
                            @if ($user->voice)
                                {{ $user->voice->name }}
                            @else
                                <small class="text-muted">({{ __('None set') }})</small>
                            @endif

                            <a href="{{ route('voice.showSet', $user->id) }}" data-toggle="modal" data-target="#mainModal" class="btn-link btn-sm">
                                <span class="oi oi-pulse" data-toggle="tooltip" title="{{ __('Set Voice') }}"></span>
                            </a>
                        </div>
                        <div class="col-md-2">
                            @foreach($user->roles as $role)
                                {{ $role->display_name }}&nbsp;
                            @endforeach
                            <a href="{{ route('role.showSet', $user->id) }}" data-toggle="modal" data-target="#mainModal" class="btn-link btn-sm">
                                <span class="oi oi-key" data-toggle="tooltip" title="{{ __('Set Role') }}"></span>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ __('No users found.') }}</div>
    @endif

@endsection
