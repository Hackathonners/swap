@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
        @if ($errors->has('group_max'))
            <div role="alert" class="alert alert-danger">
                Group maximum size must be equal or bigger than minimum.
            </div>
        @endif
        <div class="card-header">List of courses</div>
        <table class="card-table table table-responsive">
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
                        <th>Min - Max</th>
                    </tr>
                    @foreach ($courses as $course)
                        <tr>
                            <td class="d-none d-sm-table-cell">{{ $course->present()->getOrdinalSemester() }}</td>
                            @if ($course->group_max > 0)
                                <td><a href="/admin/{{ $course->id }}/groups" /a>{{ $course->name }}</td>
                            @else
                                <td>{{ $course->name }}</td>
                            @endif
                            <td>
                                <form method="POST" action="/courses/{{ $course->id }}">
                                    {{ csrf_field() }}
                                    <input name="group_min" class="btn btn-outline-secondary"
                                        type="number" min="0" max="30" step="1"
                                        placeholder="min" value={{ $course->group_min }}>
                                        {{ $course->min }}
                                    </input>
                                    <input name="group_max" class="btn btn-outline-secondary"
                                        type="number" min="0" max="30" step="1"
                                        placeholder="max" value={{ $course->group_max }}>
                                        {{ $course->max }}
                                    </input>
                                    <button type="submit" class="btn btn-success" href="#">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
