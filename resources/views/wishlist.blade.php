@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1 class="mb-4">Wishlist</h1>
        </div>
        <products :products="{{ json_encode($products) }}" :user="{{ json_encode($userId) }}"></products>
    </div>

@endsection