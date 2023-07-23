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

            @if(isset($data))
                <div style="padding-top: 20px">
                    @if(empty($data))
                        <h3>No classes found.</h3>
                    @else
                        @foreach($data as $class)
                            <h3>Class {{ $class['class_name'] }} - {{ ucfirst($class['period_day']) }} ({{ $class['start_time'] }} - {{ $class['end_time'] }})</h3>
                            <div style="padding-top: 20px">
                                <table class="table table-bordered table-striped table-hover datatable datatable-User">
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
            @endif
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
            pageLength: 10,
            paging: true,
        });
    });
</script>

@endsection