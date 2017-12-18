@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ trans('Voices') }}</h2>
        @permission('manageVoices')
            <a class="btn btn-default btn-sm" href="{{ route('voices.create') }}">
                {{ trans('New Voice') }}
            </a>
        @endpermission
    </header>

    @if(count($voices) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-8">{{ trans('Name') }}</div>
                <div class="col-md-2">{{ trans('Singers') }}</div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="voices">
                @foreach($voices as $voice)
                    <li class="row">
                        <div class="col-md-8">
                            {{ $voice->name }}
                        </div>
                        <div class="col-md-2">
                            {{ count($voice->singers) }}
                        </div>
                        <div class="col-md-2">
                            @permission('manageVoices')
                                <a href="{{ route('voices.edit', $voice) }}">
                                    <span class="oi oi-pencil" title="{{ trans('Edit') }}" aria-hidden="true"></span>
                                </a>

                                {!! Form::open(['onsubmit' => 'return confirm("' . trans('Do you really want to delete this voice?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['voices.destroy', $voice->id]]) !!}
                                    <button type="submit" class="btn-link text-danger">
                                        <span class="oi oi-trash" title="{{ trans('Delete') }}" aria-hidden="true"></span>
                                    </button>
						        {!! Form::close() !!}
                            @endpermission
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ trans('No voices found.') }}</div>
    @endif

@endsection
