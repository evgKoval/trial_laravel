@extends('layouts.app')

@section('content')

    <div class="container">
        @include('layouts.search')
        <div class="row">
            <h1 class="mb-4">Best Sellers in Desktop Computers</h1>
        </div>
        <products></products>
    </div>

@endsection