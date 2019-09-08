@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    Enrollments summary
                </div>
                <div class="col text-right">
                    <div class="btn-group">
                        <a href="{{ route('enrollments.export') }}" class="btn btn-primary btn-sm">Export enrollments</a>
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('enrollments.import') }}" class="dropdown-item btn btn-sm">Import enrollments</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-table table-responsive">
            <table class="table">
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
                            <th>Enrollments</th>
                        </tr>
                        @foreach ($courses as $course)
                            <tr>
                                <td>{{ $course->present()->getOrdinalSemester() }}</td>
                                <td><a href="{{ route('students.index', $course->id) }}">{{ $course->name }}</a></td>
                                <td>{{ $course->enrollments_count }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
