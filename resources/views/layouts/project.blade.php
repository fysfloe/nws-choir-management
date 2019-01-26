@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>
            {{ $project->title }}
        </h2>

        <accept-decline
                accept-route="{{ route('project.accept', $project) }}"
                decline-route="{{ route('project.decline', $project) }}"
                :accepted="{{ json_encode($project->promises->contains(Auth::user())) }}"
                :declined="{{ json_encode($project->denials->contains(Auth::user())) }}"
        >
        </accept-decline>

        @permission('manageProjects')
        <div class="main-actions">
            <a class="btn btn-primary btn-sm" href="{{ route('project.edit', $project) }}">
                <span class="oi oi-pencil"></span> {{ __('Edit') }}
            </a>
            {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this project?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['project.delete', $project->id]]) !!}
            <button type="submit" class="btn btn-danger btn-sm">
                <span class="oi oi-trash"></span> {{ __('Delete') }}
            </button>
            {!! Form::close() !!}
        </div>
        @endpermission
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('project.show', $project) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'rehearsals') active @endif" id="rehearsals-tab" href="{{ route('project.rehearsals', $project) }}" role="tab" aria-controls="rehearsals">
                <span class="oi oi-musical-note"></span>&nbsp;
                {{ __('Rehearsals') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'comments') active @endif" href="{{ route('project.comments', $project) }}" role="tab" aria-controls="info">
                <span class="oi oi-comment-square"></span>&nbsp;
                {{ __('Comments') }} ({{ count($project->comments) }})
            </a>
        </li>
        @permission('manageProjects')
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('project.participants', $project) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'voices') active @endif" id="voices-tab" href="{{ route('project.voices', $project) }}" role="tab" aria-controls="voices">
                <span class="oi oi-pulse"></span>&nbsp;
                {{ __('Voices') }}
            </a>
        </li>
        @endpermission
    </ul>

    @yield('projectContent')

@endsection
