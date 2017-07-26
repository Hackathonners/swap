@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">List of courses</div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Course</th>
                                <th>Enrollment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $year => $courses)
                                <th colspan="3" class="active">
                                    {{ $year }} year
                                </th>
                                @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->semester }}</td>
                                    <td>{{ $course->name }}</td>
                                    @if (Auth::user()->student->isEnrolledInCourse($course))
                                        <td>Enrolled</td>
                                    @else
                                        <td>
                                            {{-- Form to enroll in course. --}}
                                            <form action="{{ route('enrollments.create') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <button type="submit" class="btn btn-success btn-xs">Enroll</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
