@extends('auth.layout')
@section('title', 'Login')
@section('content')
<div class="container">
    <form class="ms-auto me-auto mt-auto" action="{{ route('login.post') }}" method="POST" style="width: 500px;">
        @csrf
        <div class="form-group" style="padding-bottom: 15px;">
            <label for="emailInput">Email address</label>
            <input type="email" class="form-control" id="emailInput" name="email" placeholder="Name@Example.com">
        </div>
        <div class="form-group">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Submit</button>
    </form>

</div>
@endsection