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
                <div class="col-md-4">{{ trans('Role') }}</div>
            </header>

            <ul class="users">
                @foreach($users as $user)
                    <li class="row">
                        <div class="col-md-8">
                            <a href="{{ route('users.show', $user->id) }}">{{ $user->firstname }} {{ $user->surname }}</a>
                        </div>
                        <div class="col-md-4">
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
