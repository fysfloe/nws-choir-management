@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Rehearsals') }}</h2>
        @permission('manageRehearsals')
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.create') }}">
                {{ __('New Rehearsal') }}
            </a>
        @endpermission
    </header>

    @if(count($rehearsals) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-4">{{ __('Date') }}</div>
                <div class="col-md-2">{{ __('Promises') }} / <span class="text-muted">{{ __('Denials') }}</span></div>
                <div class="col-md-2">
                    @permission('manageRehearsals')
                        {{ __('Created by') }}
                    @endpermission
                </div>
                <div class="col-md-2">{{ __('Project') }}</div>
                <div class="col-md-1">&nbsp;</div>
                <div class="col-md-1">&nbsp;</div>
            </header>

            <ul class="rehearsals">
                @foreach($rehearsals as $rehearsal)
                    <li class="row">
                        <div class="col-md-4">
                            {!! $rehearsal->__toString() !!}&nbsp;
                            <a href="{{ route('rehearsal.show', $rehearsal) }}">
                                <span class="oi oi-eye" data-toggle="tooltip" title="{{ __('Show') }}"></span>
                            </a>
                        </div>
                        <div class="col-md-2">
                            {{ count($rehearsal->promises) }} / <span class="text-muted">{{ count($rehearsal->denials) }}</span>
                        </div>
                        <div class="col-md-2">
                            @permission('manageRehearsals')
                                {{ $rehearsal->creator->firstname }} {{ $rehearsal->creator->surname }}
                            @endpermission
                        </div>
                        <div class="col-md-2">
                            @if ($rehearsal->project)
                                {{ $rehearsal->project->title }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="col-md-1">
                            <div class="btn-group" role="group" aria-label="{{ __('Accept or decline') }}">
                                <a href="{{ route('rehearsal.accept', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->promises->contains(Auth::user()) ? 'btn-success' : 'btn-default' }}">
                                    <span class="oi oi-check" title="{{ __('Accept') }}" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('rehearsal.decline', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->denials->contains(Auth::user()) ? 'btn-danger' : 'btn-default' }}">
                                    <span class="oi oi-x" title="{{ __('Decline') }}" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-1">
                            @permission('manageRehearsals')
                                <a href="{{ route('rehearsal.edit', $rehearsal) }}">
                                    <span class="oi oi-pencil" data-toggle="tooltip" title="{{ __('Edit') }}" aria-hidden="true"></span>
                                </a>

                                {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this rehearsal?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['rehearsal.delete', $rehearsal->id]]) !!}
                                    <button type="submit" class="btn-link text-danger">
                                        <span class="oi oi-trash" data-toggle="tooltip" title="{{ __('Delete') }}" aria-hidden="true"></span>
                                    </button>
						        {!! Form::close() !!}
                            @endpermission
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ __('No rehearsals found.') }}</div>
    @endif

@endsection
