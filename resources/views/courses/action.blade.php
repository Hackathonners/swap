<div class="btn-group">
    @if (Auth::user()->student->isEnrolledInCourse($course))
        <button type="button" class="btn btn-outline-secondary btn-sm disabled">Enrolled</button>

        @if ($settings->withinEnrollmentPeriod())
            {{-- Show dropdown to remove enrollment in course. --}}
            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
            </ul>
        @endif
    @elseif ($settings->withinEnrollmentPeriod())
        {{-- Show button to enroll in course. --}}
        <form action="{{ route('enrollments.create') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <button type="submit" class="btn btn-success btn-sm">Enroll in course</button>
        </form>
    @endif
</div>
