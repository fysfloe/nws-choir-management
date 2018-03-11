@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>{{ __('Dashboard') }}</h2>
    </header>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row dashboard">
        <div class="col-8">
            <h3>
                <span class="oi oi-calendar"></span> {{ __('Current Semester') }}
                @if ($currentSemester)
                    <small class="text-muted">{{ $currentSemester->__toString() }}</small>
                @endif
            </h3>
            @if ($currentSemester)
                @if ($currentSemester->participants->contains(Auth::user()))
                    <div class="margin-bottom alert alert-sm {{ $currentSemester->promises->contains(Auth::user()) ? 'alert-success' : ($currentSemester->denials->contains(Auth::user()) ? 'alert-danger' : '') }}">
                        @if ($currentSemester->promises->contains(Auth::user()))
                            {{ __('You are attending!') }}
                        @elseif ($currentSemester->denials->contains(Auth::user()))
                            {{ __('You are not attending.') }}
                        @endif
                    </div>
                @endif
                <div class="row">
                    <div class="col">
                        <h4 class="margin-top">
                            <span class="oi oi-project"></span>&nbsp;
                            {{ __('Projects') }}
                            <small>(<a href="{{ route('projects') }}">{{ __('Show all') }}</a>)</small>
                        </h4>
                        @if (count($currentSemester->projects) > 0)
                            <ul class="concerts">
                                @foreach ($currentSemester->projects as $project)
                                    <li>
                                        <div class="row">
                                            <div class="col">
                                                <span class="accepted-sign oi oi-media-record {{ $project->promises->contains(Auth::user()->id) ? 'text-success' : ($project->denials->contains(Auth::user()->id) ? 'text-danger' : 'text-muted') }}"
                                                    data-toggle="tooltip"
                                                    title="{{ $project->promises->contains(Auth::user()->id) ? __('You are attending!') : ($project->denials->contains(Auth::user()->id) ? __('You are not attending.') : __('You didn\'t tell us yet, if you are attending this project.')) }}"
                                                ></span>&nbsp;
                                                <a href="{{ route('project.show', $project) }}">{{ $project->title }}</a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <small class="text-muted">{{ __('No projects this semester.') }}</small>
                        @endif

                        @permission('manageProjects')
                        <div class="margin-top">
                            <a class="btn btn-default btn-sm" href="{{ route('project.create', ['semester' => $currentSemester]) }}">{{ __('Add a project') }}</a>
                        </div>
                        @endpermission
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4 class="margin-top">
                            <span class="oi oi-musical-note"></span>&nbsp;
                            {{ __('Concerts') }}
                            <small>(<a href="{{ route('concerts') }}">{{ __('Show all') }}</a>)</small>
                        </h4>
                        @if (count($currentSemester->concerts) > 0)
                            <ul class="concerts">
                                @foreach ($currentSemester->concerts as $concert)
                                    <li>
                                        <div class="row">
                                            <div class="col">
                                                <span class="accepted-sign oi oi-media-record {{ $concert->promises->contains(Auth::user()->id) ? 'text-success' : ($concert->denials->contains(Auth::user()->id) ? 'text-danger' : 'text-muted') }}"
                                                    data-toggle="tooltip"
                                                    title="{{ $concert->promises->contains(Auth::user()->id) ? __('You are attending!') : ($concert->denials->contains(Auth::user()->id) ? __('You are not attending.') : __('You didn\'t tell us yet, if you are attending this concert.')) }}"
                                                ></span>&nbsp;
                                                <a href="{{ route('concert.show', $concert) }}">{{ $concert->title }}</a>
                                            </div>
                                            <div class="col">
                                                <small>
                                                    <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}&nbsp;
                                                    <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}&nbsp;
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <small class="text-muted">{{ __('No concerts this semester.') }}</small>
                        @endif

                        @permission('manageConcerts')
                        <div class="margin-top">
                            <a class="btn btn-default btn-sm" href="{{ route('concert.create', ['semester' => $currentSemester]) }}">{{ __('Add a concert') }}</a>
                        </div>
                        @endpermission
                    </div>
                    <div class="col">
                        <h4 class="margin-top">
                            <span class="oi oi-infinity"></span>&nbsp;
                            {{ __('Rehearsals') }}
                            <small>(<a href="{{ route('rehearsals') }}">{{ __('Show all') }}</a>)</small>
                        </h4>
                        @if (count($currentSemester->nextRehearsals()) > 0)
                            <ul class="rehearsals">
                                @foreach ($currentSemester->nextRehearsals() as $rehearsal)
                                    <li>
                                        <span class="accepted-sign oi oi-media-record {{ $rehearsal->promises->contains(Auth::user()->id) ? 'text-success' : ($rehearsal->denials->contains(Auth::user()->id) ? 'text-danger' : 'text-muted') }}"
                                            data-toggle="tooltip"
                                            title="{{ $rehearsal->promises->contains(Auth::user()->id) ? __('You are attending!') : ($rehearsal->denials->contains(Auth::user()->id) ? __('You are not attending.') : __('You didn\'t tell us yet, if you are attending this rehearsal.')) }}"
                                        ></span>&nbsp;
                                        <a href="{{ route('rehearsal.show', $rehearsal) }}">{!! $rehearsal->__toString() !!}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <small class="text-muted">{{ __('No (more) rehearsals this semester.') }}</small>
                        @endif

                        @permission('manageRehearsals')
                        <div class="margin-top">
                            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.create', ['semester' => $currentSemester]) }}">{{ __('Add a rehearsal') }}</a>
                        </div>
                        @endpermission

                    </div><!-- .col -->
                </div><!-- .row -->
            @else
                <small class="text-muted">{{ __('No info about the current semester.') }}</small>

                @permission('manageSemesters')
                <div class="margin-top">
                    <a class="btn btn-default btn-sm" href="{{ route('semesters.create') }}">
                        {{ __('New Semester') }}
                    </a>
                </div>
                @endpermission
            @endif
        </div><!-- .col -->
        <div class="col-4 side-box">
            <div>
                <h3><span class="oi oi-task"></span> {{ __('To do') }}</h3>
                @if (!Auth::user()->voice || ($currentSemester && !$currentSemester->promises->contains(Auth::user()->id)))
                    <ul class="todos">
                        @if (!Auth::user()->voice)
                            <li>
                                {{ __('You didn\'t set your voice yet.') }}&nbsp;
                                <a href="{{ route('voice.showSet', Auth::user()) }}" data-toggle="modal" data-target="#mainModal">{{ __('Do it now!') }}</a>
                            </li>
                        @endif

                        @if ($currentSemester && !$currentSemester->participants->contains(Auth::user()->id))
                            <li>
                                <p>
                                    {{ __('You didn\'t tell us yet, if you are attending this semester.') }}
                                </p>
                                <div class="btn-group accept-decline" role="group" aria-label="{{ __('Accept or decline') }}">
                                    <a href="{{ route('semester.accept', $currentSemester) }}" class="btn btn-sm btn-default">
                                        <span class="oi oi-check" aria-hidden="true"></span> {{ __('Attend') }}
                                    </a>
                                    <a href="{{ route('semester.decline', $currentSemester) }}" class="btn btn-sm btn-default">
                                        <span class="oi oi-x" aria-hidden="true"></span> {{ __('Decline') }}
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                @else
                    <small class="text-muted">{{ __('Nothing to do for now!') }}</small>
                @endif
            </div>
        </div><!-- col-4 -->
    </div><!-- .row -->
@endsection
