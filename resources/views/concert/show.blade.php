@extends('layouts.concert')

@section('concertContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ __('Description') }}</h3>
                    @if ($concert->description)
                        {{ $concert->description }}
                    @else
                        <small class="text-muted">{{ __('No description added.') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-4 side-box">
                @if ($concert->project)
                    <h3>{{ __('Project') }}</h3>
                    <a href="{{ route('project.show', $concert->project) }}">{{ $concert->project->title }}</a>
                @endif

                <h3 class="{{ $concert->project ? 'mt-4' : '' }}">{{ __('Date') }}</h3>
                <div>
                    <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}&nbsp;
                    <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}@if ($concert->end_time)–{{ date_format(date_create($concert->end_time), 'H:i') }}@endif
                </div>

                <h3 class="mt-4">{{ __('Rehearsals') }}</h3>
                @if ($concert->project && count($concert->project->rehearsals) > 0)
                    <ul class="rehearsals">
                    @foreach ($concert->project->rehearsals as $rehearsal)
                        <li>
                            {!! $rehearsal->__toString() !!}&nbsp;
                            <a href="{{ route('rehearsal.show', $rehearsal) }}">
                                <span class="oi oi-eye" data-toggle="tooltip" title="{{ __('Show') }}"></span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No rehearsals found that belong to the projects concert.') }}</small>
                @endif

                @permission('manageRehearsals')
                <a class="btn btn-default btn-sm mt-2" href="{{ route('rehearsal.create', ['semester' => $concert->semester->id, 'concert' => $concert->id]) }}">
                    {{ __('Add a rehearsal') }}
                </a>
                @endpermission
            </div>
        </div>
    </div>

@endsection
