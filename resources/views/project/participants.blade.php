@extends('layouts.project')

@section('projectContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        @permission('manageProjects')
        <div class="clearfix my-3 text-right">
            <a class="btn btn-default btn-sm" href="{{ route('project.showAddUser', $project) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-plus"></span> {{ __('Add a participant') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('project.exportParticipants', $project, app('request')->all()) }}">
                <span class="oi oi-account-login"></span> {{ __('Export') }}
            </a>
        </div>
        @endpermission

        <user-list
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-roles="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('project.loadParticipants', $project) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('project.setUserVoice', $project) }}"
            remove-participant-route="{{ route('project.removeParticipant', $project) }}"
            remove-participants-route="{{ route('project.removeParticipants', $project) }}"
            :actions="{{ json_encode(['removeParticipant', 'setVoice', 'editProfile']) }}"
        ></user-list>
    </div>

@endsection
