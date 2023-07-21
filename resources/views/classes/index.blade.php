@extends('layouts.master')
@section('content')
<div class="content">

    <div class="card">
        <div class="card-header">
            Classes
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route("classes.getclasses") }}">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col" style="min-width: fit-content;">
                        <label for="teacher_id">Select Teacher:</label>
                        <select class="form-control select2" name="teacher_id" id="teacher_id">
                            @foreach($teachers as $id => $details)
                                <option value="{{ $details['id'] }}">{{ $details['title'] }}. {{ $details['forename'] }} {{ $details['surname'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-lg btn-primary" type="submit" style="margin-top: 20px">Submit</button>
                    </div>
                </div>
            </form>

            <!-- Add scenario for user with no classes -->
            @if(isset($data))
                @foreach($data as $class)
                    <h3>{{ $class['class_name'] }}</h3>
                    <button class="accordion">
                    </button>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class['students'] as $id => $student)
                                    <tr>
                                        <td>{{ $student['forename'] }}</td>
                                        <td>{{ $student['surname'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


</div>
@endsection
@section('scripts')
@parent
@endsection