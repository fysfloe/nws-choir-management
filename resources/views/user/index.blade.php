@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>Users</h2>
        @permission('manageUsers')
            <a class="btn btn-default btn-sm" href="{{ route('users.create') }}">
                {{ trans('New User') }}
            </a>
        @endpermission
    </header>

    @if(count($users) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-8">{{ trans('User') }}</div>
                <div class="col-md-2">{{ trans('Voice') }}</div>
                <div class="col-md-2">{{ trans('Role') }}</div>
            </header>

            <ul class="users">
                @foreach($users as $user)
                    <li class="row">
                        <div class="col-md-8">
                            <a href="{{ route('users.show', $user->id) }}">{{ $user->firstname }} {{ $user->surname }}</a>
                        </div>
                        <div class="col-md-2">
                            @if (count($user->voices) > 0)
                                {{ $user->voices->first()->name }}
                            @else
                                <a href="{{ route('voice.showSet', $user->id) }}">{{ trans('Set Voice') }}</a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @foreach($user->roles as $role)
                                {{ $role->display_name }}&nbsp;
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ trans('No users found.') }}</div>
    @endif

@endsection
