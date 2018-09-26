@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Project Participants -->
    <div class="tab-pane fade show active" id="participants" role="tabpanel" aria-labelledby="participants-tab">
        <user-list
            :texts="{{ json_encode([
                'noActiveFilters' => __('No active filters'),
                'showHideFilters' => __('Show/hide filters'),
                'applyFilters' => __('Apply Filters'),
                'labels' => [
                    'search' => __('Search'),
                    'voices' => __('Voices'),
                    'concerts' => __('Concerts'),
                    'age' => __('Age')
                ],
                'actions' => [
                    'archive' => __('Archive'),
                    'setVoice' => __('Set Voice'),
                    'setRole' => __('Set Role'),
                    'confirmArchiveMulti' => __('Do you really want to archive these users?'),
                    'confirmArchive' => __('Do you really want to archive this user?')
                ],
                'headings' => [
                    'user' => __('User'),
                    'voice' => __('Voice'),
                    'role' => __('Role')
                ],
                'editProfile' => __('Edit Profile'),
                'noneSet' => __('None set'),
                'noUsers' => __('No users found.'),
                'total' => __('Total'),
                'sortBy' => __('Sort by'),
                'present' => __('Present'),
                'excused' => __('Excused'),
                'unexcused' => __('Unexcused'),
                'acceptOrDecline' => __('Accept or decline'),
                'attending' => __('This user is attending!'),
                'accept' => __('Accept'),
                'notAttending' => __('This user is not attending.'),
                'decline' => __('Decline')
            ]) }}"
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-role="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('rehearsal.loadParticipants', $rehearsal) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('voice.showSet') }}"
            :with-accept-decline="true"
            :accept-decline-routes="{{ json_encode([
                'accept' => route('rehearsal.accept', $rehearsal),
                'decline' => route('rehearsal.decline', $rehearsal)
            ]) }}"
            :promises="{{ json_encode($rehearsal->promises->toArray()) }}"
            :denials="{{ json_encode($rehearsal->denials->toArray()) }}"
        ></user-list>
    </div>

@endsection
