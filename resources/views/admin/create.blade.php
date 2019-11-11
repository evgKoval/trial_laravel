@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Add a product</h1>
        <b-form method="POST" action="/admin">
            {{ csrf_field() }}
            <b-form-group label="Name:" label-for="input-name">
                <b-form-input
                    name="name"
                    id="input-name"
                    placeholder="Enter name"
                ></b-form-input>
            </b-form-group>

            <b-form-group
                label="Price:"
                label-for="input-price"
            >
                <b-form-input
                    name="price"
                    id="input-price"
                    placeholder="Enter price"
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Image:" label-for="input-image">
                <b-form-input
                    name="image"
                    id="input-image"
                    placeholder="Enter image url"
                ></b-form-input>
            </b-form-group>

            <b-form-group label="Category:" label-for="input-category">
                <b-form-input
                    name="category"
                    id="input-category"
                    placeholder="Enter category"
                ></b-form-input>
            </b-form-group>

            <b-button type="submit" variant="primary" block>Add</b-button>
        </b-form>
    </div>
@endsection
