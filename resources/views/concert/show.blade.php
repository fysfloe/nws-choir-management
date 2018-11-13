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

                @if ($concert->place)
                    <h3 class="mt-4">{{ __('Place') }}</h3>
                    <div>
                        {{ $concert->place }}
                    </div>
                @endif

                <h3 class="mt-4">{{ __('Rehearsals') }}</h3>
                @if ($concert->project && count($concert->project->rehearsals) > 0)
                    <ul class="rehearsals">
                    @foreach ($concert->project->rehearsals as $rehearsal)
                    <a href="{{ route('rehearsal.show', $rehearsal) }}">
                        <li>
                            <span>{!! $rehearsal->twoLineString() !!}</span>
                            @if ($rehearsal->date > new \DateTime())
                                <accept-decline
                                    accept-route="{{ route('rehearsal.accept', $rehearsal) }}"
                                    decline-route="{{ route('rehearsal.decline', $rehearsal) }}"
                                    :accepted="{{ json_encode($rehearsal->promises->contains(Auth::user())) }}"
                                    :declined="{{ json_encode($rehearsal->denials->contains(Auth::user())) }}"
                                    :texts="{{ json_encode([
                                        'acceptOrDecline' => __('Accept or decline'),
                                        'attending' => __('You are attending!'),
                                        'accept' => __('Accept'),
                                        'notAttending' => __('You are not attending.'),
                                        'decline' => __('Decline')
                                    ]) }}"
                                >
                                </accept-decline>
                            @else 
                                <span class="accept-decline oi oi-media-record {{ $rehearsal->promises->contains(Auth::user()) ? 'text-success' : ($rehearsal->denials->contains(Auth::user()) ? 'text-danger' : 'text-muted') }}"></span>
                            @endif
                        </li>
                    </a>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No rehearsals found that belong to the projects concert.') }}</small>
                @endif

                @permission('manageRehearsals')
                <a class="btn btn-primary btn-sm mt-2" href="{{ route('rehearsal.create', ['project' => $concert->project->id, 'concert' => $concert->id]) }}">
                    {{ __('Add a rehearsal') }}
                </a>
                @endpermission
            </div>
        </div>
    </div>

@endsection
