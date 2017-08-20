<div class="btn-group">
    {{-- Show button to exchange shift, if exchanges period is active --}}
    @if ($settings->withinExchangePeriod() && $enrollment->exchanges_as_source_count < 1 && !is_null($enrollment->shift))
        <a href="{{ route('exchanges.create', $enrollment->id )}}" class="btn btn-secondary btn-sm">Exchange shift</a>
    @endif

    {{-- Show enrollment actions, if enrollments period is active --}}
    @if ($settings->withinEnrollmentPeriod())
        <button type="button" class="btn btn-outline-secondary btn-sm disabled">Enrolled</button>
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <form action="{{ route('enrollments.destroy') }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input type="hidden" name="course_id" value="{{ $enrollment->course_id }}">
                <button type="submit" class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
            </form>
        </div>
    @endif
</div>
