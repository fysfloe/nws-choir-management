@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Projects') }}</h2>
        @permission('manageProjects')
            <a class="btn btn-default btn-sm" href="{{ route('project.create') }}">
                {{ __('New Project') }}
            </a>
        @endpermission
    </header>

    @include('project._filters')

    @if(count($projects) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-4">
                    {{ __('Title') }}
                    <a class="list-sort {{ app('request')->input('sort') === 'title' && app('request')->input('dir') === 'ASC' ? 'active' : '' }}" href="{{ route('projects', ['sort' => 'title', 'dir' => 'ASC']) }}">
                        <span class="oi oi-caret-top"></span>
                    </a>
                    <a class="list-sort {{ app('request')->input('sort') === 'title' && app('request')->input('dir') === 'DESC' ? 'active' : '' }}" href="{{ route('projects', ['sort' => 'title', 'dir' => 'DESC']) }}">
                        <span class="oi oi-caret-bottom"></span>
                    </a>
                </div>
                <div class="col-md-3">
                    {{ __('Semester') }}
                </div>
                <div class="col-md-3">
                    @permission('manageProjects')
                        {{ __('Created by') }}
                    @endpermission
                </div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="projects">
                @foreach($projects as $project)
                    <a href="{{ route('project.show', $project->slug) }}">
                        <li class="row">
                            <div class="col-md-4">
                                {{ $project->title }}
                            </div>
                            <div class="col-md-3">
                                {{ $project->semester }}
                            </div>
                            <div class="col-md-3">
                                @permission('manageProjects')
                                    {{ $project->creator->firstname }} {{ $project->creator->surname }}
                                @endpermission
                            </div>
                            <div class="col-md-2">
                                @permission('manageProjects')
                                    {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this project?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['project.delete', $project->id]]) !!}
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            {{ __('Delete') }}
                                        </button>
                                    {!! Form::close() !!}
                                @endpermission
                            </div>
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">
            <small class="text-muted">{{ __('No projects found.') }}</small>
        </div>
    @endif

@endsection
