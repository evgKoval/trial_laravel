@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1 class="mb-4">Wishlist</h1>
        </div>
        <products :products="{{ json_encode($products) }}"></products>
    </div>

@endsection