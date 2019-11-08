@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1 class="mb-4">Admin page</h1>
        </div>
        <admin-products :products="{{ json_encode($products) }}"></admin-products>
    </div>

@endsection