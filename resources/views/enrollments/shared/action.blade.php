<div class="btn-group">
    {{-- Show button to exchange shift, if exchanges period is active --}}
    @if ($settings->withinExchangePeriod())
        <button type="button" class="btn btn-secondary btn-sm">Exchange shift</button>
    @endif

    {{-- Show enrollment actions, if enrollments period is active --}}
    @if ($settings->withinEnrollmentPeriod())
        @if (! Auth::user()->student->isEnrolledInCourse($course))
            {{-- Show button to enroll in course. --}}
            <form action="{{ route('enrollments.create') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <button type="submit" class="btn btn-success btn-sm">Enroll in course</button>
            </form>
        @else
            <button type="button" class="btn btn-secondary btn-sm disabled">Enrolled</button>
            <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
            </ul>
        @endif
    @endif
</div>
