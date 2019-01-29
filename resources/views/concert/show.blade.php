@extends('layouts.concert')

@section('concertContent')

    <!-- Info -->
    <concert-details
            :concert="{{ $concertJson }}"
            :user="{{ $user }}"
    ></concert-details>

@endsection