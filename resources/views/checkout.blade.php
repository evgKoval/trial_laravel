@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <b-col sm="12">
                <h1 class="mb-4">Payment Form</h1>
                @if ($message = Session::get('success'))
                    <div class="w3-panel w3-green w3-display-container">
                        <span onclick="this.parentElement.style.display='none'"
                                class="w3-button w3-green w3-large w3-display-topright">&times;</span>
                        <p>{!! $message !!}</p>
                    </div>
                    <?php Session::forget('success');?>
                @endif

                @if ($message = Session::get('error'))
                    <div class="w3-panel w3-red w3-display-container">
                        <span onclick="this.parentElement.style.display='none'"
                                class="w3-button w3-red w3-large w3-display-topright">&times;</span>
                        <p>{!! $message !!}</p>
                    </div>
                    <?php Session::forget('error');?>
                @endif

                <b-form method="POST" id="payment-form" action="{{ route('paypal') }}">
                    {{ csrf_field() }}
                    <b-form-group
                        label="Enter amount:"
                        label-for="input-pay"
                    >
                        <b-form-input
                            name="amount"
                            id="input-pay"
                            value="{{ $amount }}"
                        ></b-form-input>
                    </b-form-group>

                    <b-button type="submit" variant="primary" block>Pay with PayPal</b-button>
                </b-form>
            </b-col>
        </div>
    </div>
@endsection
