@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <b-col sm="12">
                <h1 class="mb-4">Payment Form</h1>
                <b-form method="POST" id="payment-form" action="{{ route('paypal') }}">
                    {{ csrf_field() }}
                    <b-form-group
                        label="Your name"
                        label-for="input-name"
                    >
                        <b-form-input
                            name="name"
                            id="input-name"
                            value="{{ $user->name }}"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                        label="Your email"
                        label-for="input-email"
                    >
                        <b-form-input
                            name="email"
                            id="input-email"
                            value="{{ $user->email }}"
                        ></b-form-input>
                    </b-form-group>

                    <div class="row">
                        <b-col col="3">
                            <b-form-group
                                label="Your country"
                                label-for="input-country"
                            >
                                <b-form-input
                                    name="country"
                                    id="input-country"
                                    value="{{ old('country') }}"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col col="3">
                            <b-form-group
                                label="State code"
                                label-for="input-code"
                            >
                                <b-form-input
                                    name="code"
                                    id="input-code"
                                    value="{{ old('code') }}"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-form-group
                            label="Your city"
                            label-for="input-city"
                        >
                            <b-form-input
                                name="city"
                                id="input-city"
                                value="{{ old('city') }}"
                            ></b-form-input>
                        </b-form-group>
                        <b-col col="3">
                            <b-form-group
                                label="ZIP"
                                label-for="input-zip"
                            >
                                <b-form-input
                                    name="zip"
                                    id="input-zip"
                                    value="{{ old('zip') }}"
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </div>

                    <b-form-group
                        label="Your adress"
                        label-for="input-adress"
                    >
                        <b-form-input
                            name="adress"
                            id="input-adress"
                            value="{{ old('adress') }}"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                        label="Amount"
                        label-for="input-pay"
                    >
                        <b-form-input
                            readonly
                            name="amount"
                            id="input-pay"
                            value="${{ $amount }}"
                        ></b-form-input>
                    </b-form-group>

                    <b-button type="submit" variant="primary" block>Pay with PayPal</b-button>
                </b-form>
            </b-col>
        </div>
    </div>
@endsection
