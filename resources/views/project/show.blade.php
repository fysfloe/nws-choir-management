@extends('layouts.project')

@section('projectContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ __('Description') }}</h3>
                    @if ($project->description)
                        {{ $project->description }}
                    @else
                        <small class="text-muted">{{ __('No description added.') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-4 side-box">
                <h3>{{ __('Concerts') }} <span class="badge badge-pill badge-secondary">{{ count($project->concerts) }}</span></h3>
                @if (count($project->concerts) > 0)
                    <ul class="concerts">
                    @foreach ($project->concerts as $concert)
                        <li>
                            <a href="{{ route('concert.show', $concert) }}">{{ $concert->title }}</a>&nbsp;
                            <small>
                                <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}&nbsp;
                                <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}&nbsp;
                            </small>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No concerts for this project found.') }}</small>
                @endif

                @permission('manageConcerts')
                <a class="btn btn-default btn-sm mt-2" href="{{ route('concert.create', ['semester' => $project->semester->id, 'project' => $project->id]) }}">
                    {{ __('Add a concert') }}
                </a>
                @endpermission

                <h3 class="mt-4">{{ __('Rehearsals') }} <span class="badge badge-pill badge-secondary">{{ count($project->rehearsals) }}</span></h3>
                @if (count($project->rehearsals) > 0)
                    <ul class="rehearsals">
                    @foreach ($project->rehearsals as $rehearsal)
                        <li>
                            {!! $rehearsal->__toString() !!}&nbsp;
                            <a href="{{ route('rehearsal.show', $rehearsal) }}">
                                <span class="oi oi-eye" data-toggle="tooltip" title="{{ __('Show') }}"></span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No rehearsals for this project found.') }}</small>
                @endif

                @permission('manageRehearsals')
                <a class="btn btn-default btn-sm mt-2" href="{{ route('rehearsal.create', ['semester' => $project->semester->id, 'project' => $project->id]) }}">
                    {{ __('Add a rehearsal') }}
                </a>
                @endpermission
            </div>
        </div>
    </div>

@endsection
