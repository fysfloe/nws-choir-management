@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Projects') }}</h2>

        @permission('manageProjects')
        <div class="main-actions">
            <a class="btn btn-primary btn-sm" href="{{ route('project.create') }}">
                <span class="oi oi-plus"></span> {{ __('New Project') }}
            </a>
        </div>
        @endpermission
    </header>

    <project-list
        :can-manage-projects="{{ Auth::user()->can('manageProjects') ? 1 : 0 }}"
        fetch-action="{{ route('project.loadItems') }}"
        :sort-options="{{ json_encode([
            'title' => __('Title')
        ]) }}"
        :actions="{{ json_encode(['remove', 'edit']) }}"
    ></project-list>

@endsection
