@extends('layouts.project')

@section('projectContent')

    <!-- Voices -->
    <div class="tab-pane fade show active" id="voices" role="tabpanel" aria-labelledby="voices-tab">
        @permission('manageProjects')
        <div class="clearfix">
            <a class="btn btn-default btn-sm" href="{{ route('project.editVoices', $project) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-pencil"></span> {{ __('Edit voices') }}
            </a>
        </div>
        @endpermission

        @if (count($project->voices) > 0)
            <ul class="voices">
                @foreach ($project->voices as $voice)
                    <li>
                        <strong>{{ $voice->name }}:</strong>&nbsp;
                        <span data-toggle="tooltip" title="{{ __('Attendants') }}">{{ $project->voiceCount($voice->id) }}</span> / <span data-toggle="tooltip" title="{{ __('Needed') }}">{{ $voice->pivot->number }}</span>
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
