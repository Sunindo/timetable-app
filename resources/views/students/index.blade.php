@extends('layouts.master')
@section('content')
<div class="content">

    <div class="card">
        <div class="card-header">
            Students
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Lesson">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        @foreach($weekDays as $index => $day) 
                            <th style="text-align: center; width: 15%">{{ ucfirst($day) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeklyStudents as $id => $student)
                        <tr>
                            <td>{{ $student['details']['forename'] }} {{ $student['details']['surname'] }}</td>
                            @foreach($weekDays as $index => $day) 
                                <td style="text-align: center;">
                                    @if($student['weekdays'][$day])
                                        <span><i class="fas fa-check"></i></span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@section('scripts')
@parent

@endsection