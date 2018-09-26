@extends('layouts.app')

@section('content')

    <header class="page-header">
        <div>
            <h2>
                {{ __('Rehearsal') }}: {{ $rehearsal->date->format('d.m.Y') }}
                @permission('manageRehearsals')
                    <a class="btn btn-link" href="{{ route('rehearsal.edit', $rehearsal) }}">
                        <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit') }}"></span>
                    </a>
                    {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this rehearsal?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['rehearsal.delete', $rehearsal->id]]) !!}
                        <button type="submit" class="btn btn-link text-danger">
                            <span class="oi oi-trash" data-toggle="tooltip" title="{{ __('Delete') }}" aria-hidden="true"></span>
                        </button>
			        {!! Form::close() !!}
                @endpermission
            </h2>
        </div>
        
        @if ($rehearsal->getDateTime() > new \DateTime())
        <accept-decline
            accept-route="{{ route('rehearsal.accept', $rehearsal) }}"
            decline-route="{{ route('rehearsal.decline', $rehearsal) }}"
            :accepted="{{ json_encode($rehearsal->promises->contains(Auth::user())) }}"
            :declined="{{ json_encode($rehearsal->denials->contains(Auth::user())) }}"
            :texts="{{ json_encode([
                'acceptOrDecline' => __('Accept or decline'),
                'attending' => __('You are attending!'),
                'accept' => __('Accept'),
                'notAttending' => __('You are not attending.'),
                'decline' => __('Decline')
            ]) }}"
        >
        </accept-decline>
        @endif
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('rehearsal.show', $rehearsal) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'comments') active @endif" href="{{ route('rehearsal.comments', $rehearsal) }}" role="tab" aria-controls="info">
                <span class="oi oi-comment-square"></span>&nbsp;
                {{ __('Comments') }} ({{ count($rehearsal->comments) }})
            </a>
        </li>
        @permission('manageRehearsals')
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('rehearsal.participants', $rehearsal) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'projectParticipants') active @endif" id="project-participants-tab" href="{{ route('rehearsal.projectParticipants', $rehearsal) }}" role="tab" aria-controls="projectParticipants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Project Participants') }}
            </a>
        </li>
        @endpermission
    </ul>

    @yield('rehearsalContent')

@endsection
