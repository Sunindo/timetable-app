@extends('layouts.master')
@section('content')
<div class="content">

    <div class="card">
        <div class="card-header">
            Your Students
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover datatable datatable-User">
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
                                        <span>
                                            <i class="fas fa-check"></i>
                                        </span>
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
<script>

    $(document).ready(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        if ($.fn.DataTable.isDataTable('.datatable-User')) {
            $('.datatable-User').DataTable().destroy();
        }

        $('.datatable-User').DataTable({
            buttons: dtButtons,
            order: [[ 0, 'asc' ]],
            pageLength: 100,
            paging: true,
        });
    });
</script>

@endsection