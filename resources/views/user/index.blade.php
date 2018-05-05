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

    <user-list
        :texts="{{ json_encode([
            'noActiveFilters' => __('No active filters'),
            'showHideFilters' => __('Show/hide filters'),
            'applyFilters' => __('Apply Filters'),
            'labels' => [
                'search' => __('Search'),
                'voices' => __('Voices'),
                'concerts' => __('Concerts'),
                'age' => __('Age')
            ],
            'actions' => [
                'archive' => __('Archive'),
                'setVoice' => __('Set Voice'),
                'setRole' => __('Set Role'),
                'confirmArchiveMulti' => __('Do you really want to archive these users?'),
                'confirmArchive' => __('Do you really want to archive this user?')
            ],
            'headings' => [
                'user' => __('User'),
                'voice' => __('Voice'),
                'role' => __('Role')
            ],
            'editProfile' => __('Edit Profile'),
            'noneSet' => __('None set'),
            'noUsers' => __('No users found.')
        ]) }}"
        :voices="{{ json_encode($voices) }}"
        :concerts="{{ json_encode($concerts) }}"
        :users="{{ json_encode($users) }}"
        :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
    ></user-list>

@endsection
