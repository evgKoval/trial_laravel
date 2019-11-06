@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1 class="mb-4">Your cart</h1>
        </div>
        <orders :products="{{ json_encode($orders) }}"></orders>
    </div>

@endsection