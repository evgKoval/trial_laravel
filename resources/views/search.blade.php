@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="container">
        @include('layouts.search')
        <div class="row">
            <h1 class="mb-4">Your query:</h1>
        </div>
        <products :query="{{ json_encode($query) }}"></products>
    </div>
@endsection
