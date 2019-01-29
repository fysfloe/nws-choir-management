@extends('layouts.rehearsal')

@section('rehearsalContent')

    <!-- Info -->
    <rehearsal-details
            :rehearsal="{{ $rehearsalJson }}"
            :user="{{ $user }}"
    ></rehearsal-details>

@endsection
