@extends('layouts.semester')

@section('semesterContent')
    @permission('manageSemesters')
        <div class="clearfix my-3 text-right">
            <a class="btn btn-default btn-sm" href="{{ route('semester.showAddUser', $semester) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-plus"></span> {{ __('Add a participant') }}
            </a>
        </div>
    @endpermission

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        <user-list
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-role="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('semester.loadParticipants', $semester) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('voice.showSet') }}"
            :actions="{{ json_encode(['editProfile', 'removeParticipant']) }}"
            remove-participant-route="{{ route('semester.removeParticipant', $semester) }}"
            remove-participants-route="{{ route('semester.removeParticipants', $semester) }}"
        ></user-list>
    </div>

@endsection