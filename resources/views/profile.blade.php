@extends('layouts.app')

@section('title', 'Your profile')

@section('content')
    <div class="container">
        <h1 class="mb-4">Your profile</h1>
        <b-form method="POST" action="{{ route('profile.edit') }}">
            {{ csrf_field() }}
            <b-form-group label="Your Name:" label-for="input-name">
                <b-form-input
                    name="name"
                    id="input-name"
                    placeholder="Enter name"
                    value="{{ $userName }}"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Email address:"
                label-for="input-email"
                description="We'll never share your email with anyone else."
            >
                <b-form-input
                    name="email"
                    id="input-email"
                    placeholder="Enter email"
                    value="{{ $userEmail }}"
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Your password:" label-for="input-password">
                <b-form-input
                    name="password"
                    id="input-password"
                    type="password"
                    placeholder="Enter password"
                ></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary" block>Edit</b-button>
        </b-form>
    </div>
@endsection
