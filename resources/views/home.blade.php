@extends('layouts.app')

@section('content')
    <header class="page-header">
        <h2>Dashboard</h2>
    </header>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div>
        You are logged in!
    </div>
@endsection
