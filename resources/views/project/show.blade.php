@extends('layouts.project')

@section('projectContent')

    <!-- Info -->
    <project-details
            :project="{{ $projectJson }}"
            :user="{{ $user }}"
    ></project-details>

@endsection
