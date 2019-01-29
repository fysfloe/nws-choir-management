@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>
            {{ $semester->__toString() }}
        </h2>

        <accept-decline
                accept-route="{{ route('semester.accept', $semester) }}"
                decline-route="{{ route('semester.decline', $semester) }}"
                :accepted="{{ json_encode($semester->promises->contains(Auth::user())) }}"
                :declined="{{ json_encode($semester->denials->contains(Auth::user())) }}"
        >
        </accept-decline>

        @permission('manageSemesters')
        <div class="main-actions">
            <a class="btn btn-primary btn-sm" href="{{ route('semesters.edit', $semester) }}">
                <span class="oi oi-pencil"></span> {{ __('Edit') }}
            </a>
        </div>
        @endpermission
    </header>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'show') active @endif" href="{{ route('semesters.show', $semester) }}" role="tab" aria-controls="info">
                <span class="oi oi-info"></span>&nbsp;
                {{ __('Info') }}
            </a>
        </li>
        @permission('manageSemesters')
        <li class="nav-item">
            <a class="nav-link @if ($tab === 'participants') active @endif" id="participants-tab" href="{{ route('semester.participants', $semester) }}" role="tab" aria-controls="participants">
                <span class="oi oi-people"></span>&nbsp;
                {{ __('Participants') }}
            </a>
        </li>
        @endpermission
    </ul>

    @yield('semesterContent')

@endsection
