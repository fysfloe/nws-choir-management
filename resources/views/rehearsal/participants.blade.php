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
                'unexcused' => __('Unexcused')
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
            :with-attendance-confirmation="true"
            :attendance-routes="{{ json_encode([
                'confirm' => route('rehearsal.confirm', ['rehearsal' => $rehearsal]),
                'excused' => route('rehearsal.excuse', ['rehearsal' => $rehearsal]),
                'unexcused' => route('rehearsal.setUnexcused', ['rehearsal' => $rehearsal])
            ]) }}"
        ></user-list>
    </div>

@endsection
