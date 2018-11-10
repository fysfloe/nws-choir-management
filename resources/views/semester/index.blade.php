@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Semesters') }}</h2>
        @permission('manageSemesters')
            <a class="btn btn-default btn-sm" href="{{ route('semesters.create') }}">
                {{ __('New Semester') }}
            </a>
        @endpermission
    </header>


    @if(count($semesters) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-3">
                    {{ __('Name') }}
                </div>
                <div class="col-md-2">
                    {{ __('Start Date') }}
                </div>
                <div class="col-md-2">
                    {{ __('End Date') }}
                </div>
                <div class="col-md-3">{{ __('Created by') }}</div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="concerts">
                @foreach($semesters as $semester)
                <a href="{{ route('semesters.show', $semester) }}">
                    <li class="row {{ $semester->isCurrent() ? 'current' : '' }}">
                        <div class="col-md-3">
                            @if ($semester->name)
                                {{ $semester->name }}
                            @else
                                <small class="text-muted">(n/a)</small>
                            @endif
                        </div>
                        <div class="col-md-2">
                            {{ date_format(date_create($semester->start_date), 'd.m.Y') }}
                        </div>
                        <div class="col-md-2">
                            {{ date_format(date_create($semester->end_date), 'd.m.Y') }}
                        </div>
                        <div class="col-md-3">
                            @if ($semester->creator)
                                {{ $semester->creator->firstname }} {{ $semester->creator->surname }}
                            @else
                                <small class="text-muted">{{ __('Automatically') }}</small>
                            @endif
                        </div>
                        <div class="col-md-2">
                            &nbsp;
                        </div>
                    </li>
                </a>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">
            <small class="text-muted">{{ __('No semesters found.') }}</small>
        </div>
    @endif

@endsection
