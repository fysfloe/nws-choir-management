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
                'sortBy' => __('Sort by')
            ]) }}"
            :users="{{ json_encode($participants) }}"
            :can-manage-users="{{ Auth::user()->can('manageUsers') }}"
            :show-role="false"
            :voices="{{ json_encode($voices) }}"
            fetch-users-action="{{ route('project.loadParticipants', $project) }}"
            :sort-options="{{ json_encode([
                'firstname' => __('Firstname'),
                'surname' => __('Surname'),
                'voice' => __('Voice'),
                'id' => __('Created at')
            ]) }}"
            set-voice-route="{{ route('project.setUserVoice', $project) }}"
        ></user-list>
    </div>

@endsection
