@extends('layouts.project')

@section('projectContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        <project-participants
                :project-id="{{ $project->id }}"
                :users="{{ json_encode($participants) }}"
                :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
                :can-manage-projects="{{ Auth::user()->can('manageProjects') }}"
                :show-roles="false"
                :voices="{{ json_encode($voices) }}"
                fetch-users-action="{{ route('project.loadParticipants', $project) }}"
                set-voice-route="{{ route('project.setUserVoice', $project) }}"
                remove-participants-route="{{ route('project.removeParticipants', $project) }}"
                :rehearsals="{{ json_encode($project->rehearsals) }}"
                :concerts="{{ json_encode($project->concerts) }}"
        ></project-participants>
    </div>

@endsection
