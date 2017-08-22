@extends('layouts.app')

@section('content')
    <div class="card card--section mb-4">
        <div class="card-header">List of courses</div>
        <table class="card-table table">
            <tbody>
                @foreach ($courses as $year => $courses)
                    <tr>
                        <th colspan="3" class="table-active">
                            {{ $year }} year
                        </th>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <th>Course</th>
                        <th></th>
                    </tr>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->present()->getOrdinalSemester() }}</td>
                            <td>{{ $course->name }}</td>
                            <td>
                                @include('courses.action', compact('course'))
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
