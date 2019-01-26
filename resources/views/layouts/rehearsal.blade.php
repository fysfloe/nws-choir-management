@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>
            {{ __('Rehearsal') }}: {{ $rehearsal->date->format('d.m.Y') }}
        </h2>

        @if ($rehearsal->getDateTime() > new \DateTime())
            <accept-decline
                    accept-route="{{ route('rehearsal.accept', $rehearsal) }}"
                    decline-route="{{ route('rehearsal.decline', $rehearsal) }}"
                    :accepted="{{ json_encode($rehearsal->promises->contains(Auth::user())) }}"
                    :declined="{{ json_encode($rehearsal->denials->contains(Auth::user())) }}"
            >
            </accept-decline>
        @endif

        @permission('manageRehearsals')
        <div class="main-actions">
            <a class="btn btn-primary btn-sm" href="{{ route('rehearsal.edit', $rehearsal) }}">
                <span class="oi oi-pencil"></span> {{ __('Edit') }}
            </a>
            {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this rehearsal?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['rehearsal.delete', $rehearsal->id]]) !!}
            <button type="submit" class="btn btn-danger btn-sm">
                <span class="oi oi-trash"></span> {{ __('Delete') }}
            </button>
            {!! Form::close() !!}
        </div>
        @endpermission
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
        @endpermission
    </ul>

    @yield('rehearsalContent')

@endsection
