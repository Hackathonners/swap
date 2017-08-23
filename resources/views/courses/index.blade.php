@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
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
                        <th class="d-none d-sm-table-cell">Semester</th>
                        <th>Course</th>
                        <th></th>
                    </tr>
                    @foreach ($courses as $course)
                        <tr>
                            <td class="d-none d-sm-table-cell">{{ $course->present()->getOrdinalSemester() }}</td>
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
