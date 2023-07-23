@extends('auth.layout')
@section('title', 'Login')
@section('content')
<div class="container">
    <form action="{{ route('login.post') }}" method="POST" style="width: 500px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
        @csrf
        <div class="form-group" style="padding-bottom: 15px;">
            <label for="emailInput">Email address</label>
            <input type="email" class="form-control" id="emailInput" name="email" placeholder="Name@Example.com">
        </div>
        <div class="form-group" style="padding-bottom: 10px;">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password">
        </div>
        <div class="form-group" style="padding-bottom: 5px;">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        If you do not have an account and would like to register, click <a href="{{ route("registration") }}">here</a>
    </form>

</div>
@endsection