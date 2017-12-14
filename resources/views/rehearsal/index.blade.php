@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Rehearsals') }}</h2>
        @permission('manageRehearsals')
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.create') }}">
                {{ trans('New Rehearsal') }}
            </a>
        @endpermission
    </header>

    @if(count($rehearsals) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-4">{{ trans('Date') }}</div>
                <div class="col-md-4">{{ trans('Promises') }} / <span class="text-muted">{{ trans('Denials') }}</span></div>
                <div class="col-md-2">
                    @permission('manageRehearsals')
                        {{ trans('Created by') }}
                    @endpermission
                </div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="rehearsals">
                @foreach($rehearsals as $rehearsal)
                    <li class="row">
                        <div class="col-md-4">
                            {{ $rehearsal->date->format('d.m.Y') }} | {{ $rehearsal->date->format('H:i') }}
                            @permission('manageRehearsals')
                                <a href="{{ route('rehearsal.edit', $rehearsal) }}">
                                    <span class="oi oi-pencil" title="{{ trans('Edit') }}" aria-hidden="true"></span>
                                </a>

                                {!! Form::open(['onsubmit' => 'return confirm("' . trans('Do you really want to delete this rehearsal?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['rehearsal.delete', $rehearsal->id]]) !!}
                                    <button type="submit" class="btn-link text-danger">
                                        <span class="oi oi-trash" title="{{ trans('Delete') }}" aria-hidden="true"></span>
                                    </button>
						        {!! Form::close() !!}
                            @endpermission
                        </div>
                        <div class="col-md-4">
                            {{ count($rehearsal->promises) }} / <span class="text-muted">{{ count($rehearsal->denials) }}</span>
                        </div>
                        <div class="col-md-2">
                            @permission('manageRehearsals')
                                {{ $rehearsal->creator->firstname }} {{ $rehearsal->creator->surname }}
                            @endpermission
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group" role="group" aria-label="{{ trans('Accept or decline') }}">
                                <a href="{{ route('rehearsal.accept', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->promises->contains(Auth::user()) ? 'btn-success' : 'btn-default' }}">
                                    <span class="oi oi-check" title="{{ trans('Accept') }}" aria-hidden="true"></span>
                                </a>
                                <a href="{{ route('rehearsal.decline', $rehearsal) }}" class="btn btn-sm {{ $rehearsal->denials->contains(Auth::user()) ? 'btn-danger' : 'btn-default' }}">
                                    <span class="oi oi-x" title="{{ trans('Decline') }}" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ trans('No rehearsals found.') }}</div>
    @endif

@endsection
