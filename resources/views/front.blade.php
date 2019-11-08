@extends('layouts.app')

@section('content')

    <div class="container">
        @if ($message = Session::get('error'))
            <b-alert show variant="danger">{!! $message !!}</b-alert>
            <?php Session::forget('error');?>
        @endif
        
        @include('layouts.menu')
        @include('layouts.search')
        <div class="row">
            <h1 class="mb-4">Best Sellers from Amazon</h1>
        </div>
        <products :products="{{ json_encode($products) }}" :user="{{ json_encode($userId) }}"></products>

    </div>

@endsection