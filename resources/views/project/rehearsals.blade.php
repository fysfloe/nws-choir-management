@extends('layouts.project')

@section('projectContent')

    <!-- Rehearsals -->
    <div class="tab-pane fade show active" id="rehearsals" role="tabpanel" aria-labelledby="rehearsals-tab">
        @permission('manageRehearsals')
        <div class="clearfix my-3 text-right">
            <a class="btn btn-default btn-sm" href="{{ route('rehearsal.create', ['semester' => $project->semester->id, 'project' => $project->id]) }}">
                <span class="oi oi-plus"></span> {{ __('Add a rehearsal') }}
            </a>
        </div>
        @endpermission

        <rehearsal-list
            :can-manage-rehearsals="{{ Auth::user()->can('manageRehearsals') ? 1 : 0 }}"
            fetch-action="{{ route('rehearsal.loadItems') }}?project_id={{$project->id}}"
            :sort-options="{{ json_encode([
                'date' => __('Date')
            ]) }}"
            :actions="{{ json_encode(['remove', 'edit']) }}"
            :user="{{ Auth::user() }}"
        ></rehearsal-list>
    </div>

@endsection
