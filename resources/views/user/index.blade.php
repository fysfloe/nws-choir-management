@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Users') }}</h2>
        @permission('manageUsers')
            <a class="btn btn-default btn-sm" href="{{ route('users.create') }}">
                {{ __('New User') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('users.export', app('request')->all()) }}">
                <span class="oi oi-account-login"></span> {{ __('Export') }}
            </a>
        @endpermission
    </header>

    @include('user._filters')

    @if(count($users) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-7 has-checkbox">
                    <input type="checkbox" class="check-all" name="check-all-users" data-controls="users[]">&nbsp;

                    <div class="dropdown list-actions">
                        <a class="dropdown-toggle" href="#" role="button" id="userActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </a>

                        <div class="dropdown-menu" aria-labelledby="userActions">
                            <a class="dropdown-item" onclick="return confirm('{{ __('Do you really want to archive these users?') }} ')" data-href="{{ route('users.multiArchive') }}" href="{{ route('users.multiArchive') }}"><span class="oi oi-box"></span> {{ __('Archive') }}</a>
                            <a class="dropdown-item" data-href="{{ route('voice.showSetMulti') }}" href="{{ route('voice.showSetMulti') }}" data-toggle="modal" data-target="#mainModal"><span class="oi oi-pulse"></span> {{ __('Set Voice') }}</a>
                            <a class="dropdown-item" data-href="{{ route('role.showSetMulti') }}" href="{{ route('role.showSetMulti') }}" data-toggle="modal" data-target="#mainModal"><span class="oi oi-key"></span> {{ __('Set Role') }}</a>
                        </div>
                    </div>

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
                <div class="col-md-1">&nbsp;</div>
            </header>

            <ul class="users">
                @foreach($users as $user)
                    <li class="row">
                        <div class="col-md-7">
                            <input type="checkbox" name="users[]" value="{{ $user->id }}">&nbsp;
                            {{ $user->firstname }} {{ $user->surname }}
                            @permission('manageUsers')
                                <a href="{{ route('profile.edit', $user->id) }}" class="btn-link btn-sm">
                                    <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit Profile') }}"></span>
                                </a>
                            @endpermission
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
                        <div class="col-md-1">
                            {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to archive this user?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['users.destroy', $user]]) !!}
                                <button type="submit" class="btn btn-primary btn-xs" data-toggle="tooltip" title="{{ __('Archive') }}">
                                    <span class="oi oi-box"></span>
                                </button>
                            {!! Form::close() !!}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ __('No users found.') }}</div>
    @endif

@endsection
