@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        @permission('manageRehearsals')
        <div class="clearfix my-3 text-right">
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.addProjectParticipants', $rehearsal) }}">
                <span class="oi oi-plus"></span> {{ __('Add project participants') }}
            </a>
        </div>
        @endpermission

        <user-list
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-roles="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('rehearsal.loadParticipants', $rehearsal) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('voice.showSet') }}"
            :with-attendance-confirmation="true"
            :attendance-routes="{{ json_encode([
                'confirm' => route('rehearsal.confirm', ['rehearsal' => $rehearsal]),
                'excused' => route('rehearsal.excuse', ['rehearsal' => $rehearsal]),
                'unexcused' => route('rehearsal.setUnexcused', ['rehearsal' => $rehearsal])
            ]) }}"
            :actions="{{ json_encode(['editProfile', 'removeParticipant']) }}"
            remove-participant-route="{{ route('rehearsal.removeParticipant', $rehearsal) }}"
            remove-participants-route="{{ route('rehearsal.removeParticipants', $rehearsal) }}"
        ></user-list>
    </div>

@endsection
