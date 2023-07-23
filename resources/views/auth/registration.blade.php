@extends('auth.layout')
@section('title', 'Registration')
@section('content')
<div class="container">
    <form class="ms-auto me-auto mt-auto" action="{{ route('registration.post') }}" method="POST" style="width: 500px">
        @csrf
        <div class="form-group" style="padding-bottom: 15px;">
            <label for="nameInput">Name</label>
            <input type="text" class="form-control" id="nameInput" name="name" placeholder="John Doe">
        </div>
        <div class="form-group" style="padding-bottom: 15px;">
            <label for="emailInput">Email address</label>
            <input type="email" class="form-control" id="emailInput" name="email" placeholder="Name@Example.com">
        </div>
        <div class="form-group" style="padding-bottom: 15px;">
            <label for="passwordInput">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password">
        </div>
        <div class="form-group" style="padding-bottom: 10px;">
            <label for="teacherIdInput">Teacher ID</label>
            <input type="text" class="form-control" id="teacherIdInput" name="teacher_id" placeholder="TeacherID">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
@endsection