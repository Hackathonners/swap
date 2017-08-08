@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">List of students enrolled in <strong>{{ $course->name }}</strong></div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($enrollments->isEmpty())
                                <tr><td>No Records</td></tr>
                            @else
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->student->student_number }}</td>
                                    <td>{{ $enrollment->student->user->name }}</td>
                                    <td>{{ $enrollment->shift ? $enrollment->shift->tag : "---" }}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                        {{ $enrollments->links() }}
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
