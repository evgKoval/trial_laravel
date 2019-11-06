@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="container">
        @include('layouts.search')
        <products :query="{{ json_encode($query) }}" :products="{{ json_encode($products) }}"></products>
    </div>
@endsection
