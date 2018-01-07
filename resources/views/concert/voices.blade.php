@extends('layouts.concert')

@section('concertContent')

    <!-- Voices -->
    <div class="tab-pane fade show active" id="voices" role="tabpanel" aria-labelledby="voices-tab">
        @permission('manageConcerts')
        <div class="clearfix">
            <a class="btn btn-default btn-sm" href="{{ route('concert.editVoices', $concert) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-pencil"></span> {{ __('Edit voices') }}
            </a>
        </div>
        @endpermission

        @if (count($concert->voices) > 0)
            <ul class="voices">
                @foreach ($concert->voices as $voice)
                    <li>
                        <strong>{{ $voice->name }}:</strong>&nbsp;
                        <span data-toggle="tooltip" title="{{ __('Attendants') }}">{{ $concert->voiceCount($voice->id) }}</span> / <span data-toggle="tooltip" title="{{ __('Needed') }}">{{ $voice->pivot->number }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="no-results">
                <small class="text-muted">
                    {{ __('No voices set.') }}&nbsp;
                </small>
            </div>
        @endif
    </div>

@endsection
