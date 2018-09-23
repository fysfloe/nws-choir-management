@extends('layouts.app')

@section('content')

    <header class="page-header">
        <div>
            <h2>
                {{ $project->title }}
                @permission('manageProjects')
                    <a class="btn btn-link" href="{{ route('project.edit', $project) }}">
                        <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit') }}"></span>
                    </a>
                @endpermission
            </h2>
        </div>

        <accept-decline
            accept-route="{{ route('project.accept', $project) }}"
            decline-route="{{ route('project.decline', $project) }}"
            :accepted="{{ json_encode($project->promises->contains(Auth::user())) }}"
            :declined="{{ json_encode($project->denials->contains(Auth::user())) }}"
            :texts="{{ json_encode([
                'acceptOrDecline' => __('Accept or decline'),
                'attending' => __('You are attending!'),
                'accept' => __('Accept'),
                'notAttending' => __('You are not attending.'),
                'decline' => __('Decline'),
                'commentLabel' => __('Comment'),
                'send' => __('Send'),
                'commentSaved' => __('Comment saved!')
            ]) }}"
        >
        </accept-decline>
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('project.show', $project) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
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
