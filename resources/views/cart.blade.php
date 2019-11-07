@extends('layouts.app')

@section('content')

    <div class="container">
        @if ($message = Session::get('success'))
            <b-alert variant="success" show>{!! $message !!}</b-alert>
            <?php Session::forget('success');?>
        @endif

        @if ($message = Session::get('error'))
            <b-alert show variant="danger">{!! $message !!}</b-alert>
            <?php Session::forget('error');?>
        @endif
        <div class="row">
            <h1 class="mb-4">Your cart</h1>
        </div>
        <orders :products="{{ json_encode($orders) }}"></orders>
    </div>

@endsection