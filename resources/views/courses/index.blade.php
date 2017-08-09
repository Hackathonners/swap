@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>List of courses</strong></div>
                <table class="card-table table">
                    <tbody>
                        @foreach ($courses as $year => $courses)
                            <tr>
                                <th colspan="3" class="table-active">
                                    {{ $year }} year
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2">Course</th>
                                <th>Enrollment status</th>
                            </tr>
                            @foreach ($courses as $course)
                            <tr>
                                <td>{{ $course->semester }}</td>
                                <td>{{ $course->name }}</td>
                                @if (Auth::user()->student->isEnrolledInCourse($course))
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary btn-sm disabled">Enrolled</button>
                                            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <button class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
                                            </ul>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        {{-- Form to enroll in course. --}}
                                        <form action="{{ route('enrollments.create') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="btn btn-success btn-sm">Enroll in course</button>
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
@endsection
