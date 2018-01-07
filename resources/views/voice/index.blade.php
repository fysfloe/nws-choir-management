@extends('layouts.app')

@section('content')

    <header class="page-header">
        <h2>{{ __('Voices') }}</h2>
        @permission('manageVoices')
            <a class="btn btn-default btn-sm" href="{{ route('voices.create') }}">
                {{ __('New Voice') }}
            </a>
        @endpermission
    </header>

    @if(count($voices) > 0)
        <div class="list-table">
            <header class="row">
                <div class="col-md-8">{{ __('Name') }}</div>
                <div class="col-md-2">{{ __('Singers') }}</div>
                <div class="col-md-2">&nbsp;</div>
            </header>

            <ul class="voices">
                @foreach($voices as $voice)
                    <li class="row">
                        <div class="col-md-8">
                            {{ $voice->name }}
                        </div>
                        <div class="col-md-2">
                            {{ count($voice->users) }}
                        </div>
                        <div class="col-md-2">
                            @permission('manageVoices')
                                <a href="{{ route('voices.edit', $voice) }}">
                                    <span class="oi oi-pencil" title="{{ __('Edit') }}" aria-hidden="true"></span>
                                </a>

                                {!! Form::open(['onsubmit' => 'return confirm("' . __('Do you really want to delete this voice?') . '")', 'class' => 'form-inline', 'method' => 'DELETE', 'route' => ['voices.destroy', $voice->id]]) !!}
                                    <button type="submit" class="btn-link text-danger">
                                        <span class="oi oi-trash" title="{{ __('Delete') }}" aria-hidden="true"></span>
                                    </button>
						        {!! Form::close() !!}
                            @endpermission
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="no-results">{{ __('No voices found.') }}</div>
    @endif

@endsection
