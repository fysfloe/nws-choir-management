@extends('layouts.semester')

@section('semesterContent')

    <!-- Info -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="row">
            <div class="col-8">
                <div class="description">
                    <h3>{{ __('Description') }}</h3>
                    @if ($semester->description)
                        <!-- {{ $semester->description }} -->
                    @else
                        <small class="text-muted">{{ __('No description added.') }}</small>
                    @endif
                </div>
            </div>

            <div class="col-4 side-box">
                <h3>{{ __('Projects') }}</h3>
                @if (count($semester->projects) > 0)
                    <ul class="concerts">
                        @foreach ($semester->projects as $project)
                            <li>
                                <a href="{{ route('project.show', $project) }}">{{ $project->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <small class="text-muted">{{ __('No projects this semester.') }}</small>
                @endif
                <div class="margin-top">
                    <a class="btn btn-default btn-sm" href="{{ route('project.create', ['semester' => $semester]) }}">{{ __('Add a project') }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection
