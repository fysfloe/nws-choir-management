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
                    'confirmArchive' => __('Do you really want to archive this user?'),
                    'removeUser' => __('Remove participant'),
                    'confirmRemoveUser' => __('Do you really want to remove this participant from the semester?')
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
            fetch-users-action="{{ route('semester.loadParticipants', $semester) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('voice.showSet') }}"
            remove-user-route="{{ route('semester.removeUser', $semester) }}"
        ></user-list>
    </div>

@endsection