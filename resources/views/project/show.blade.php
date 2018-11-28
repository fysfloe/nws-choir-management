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
                    <a href="{{ route('concert.show', $concert) }}">
                        <li>
                            <div>
                                <strong>{{ $concert->title }}</strong><br>
                                <span class="oi oi-calendar text-muted"></span>&nbsp;{{ date_format(date_create($concert->date), 'd.m.Y') }}&nbsp;
                                <span class="oi oi-clock text-muted"></span>&nbsp;{{ date_format(date_create($concert->start_time), 'H:i') }}&nbsp;
                            </div>
                            @if ($concert->date > (new \DateTime())->format('Y-m-d'))
                                <accept-decline
                                    accept-route="{{ route('concert.accept', $concert) }}"
                                    decline-route="{{ route('concert.decline', $concert) }}"
                                    :accepted="{{ json_encode($concert->promises->contains(Auth::user())) }}"
                                    :declined="{{ json_encode($concert->denials->contains(Auth::user())) }}"
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
                                <span class="accept-decline oi oi-media-record {{ $concert->promises->contains(Auth::user()) ? 'text-success' : ($concert->denials->contains(Auth::user()) ? 'text-danger' : 'text-muted') }}"></span>
                            @endif
                        </li>
                    </a>
                    @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No concerts for this project found.') }}</small>
                @endif

                @permission('manageConcerts')
                <a class="btn btn-primary btn-sm mt-2" href="{{ route('concert.create', ['semester' => $project->semester->id, 'project' => $project->id]) }}">
                    {{ __('Add a concert') }}
                </a>
                @endpermission
            </div>
        </div>
    </div>

@endsection
