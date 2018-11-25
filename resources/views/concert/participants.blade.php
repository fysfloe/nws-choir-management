@extends('layouts.concert')

@section('concertContent')

    <!-- Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        @permission('manageConcerts')
        <div class="clearfix my-3 text-right">
            <a class="btn btn-default btn-sm" href="{{ route('concert.showAddUser', $concert) }}" data-toggle="modal" data-target="#mainModal">
                <span class="oi oi-plus"></span> {{ __('Add a participant') }}
            </a>
            <a class="btn btn-default btn-sm" href="{{ route('concert.exportParticipants', $concert, app('request')->all()) }}">
                <span class="oi oi-account-login"></span> {{ __('Export') }}
            </a>
        </div>
        @endpermission

        <user-list
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-role="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('concert.loadParticipants', $concert) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('concert.setUserVoice', $concert) }}"
            :actions="{{ json_encode(['editProfile', 'setVoice', 'removeParticipant']) }}"
            remove-participant-route="{{ route('concert.removeParticipant', $concert) }}"
            remove-participants-route="{{ route('concert.removeParticipants', $concert) }}"
        ></user-list>
    </div>

@endsection
