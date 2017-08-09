<div class="btn-group">
    {{-- Show button to exchange shift, if exchanges period is active --}}
    @if ($settings->withinExchangePeriod())
        <button type="button" class="btn btn-secondary btn-sm">Exchange shift</button>
    @endif

    {{-- Show enrollment actions, if enrollments period is active --}}
    @if ($settings->withinEnrollmentPeriod())
        <button type="button" class="btn btn-secondary btn-sm disabled">Enrolled</button>
        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <button class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
        </ul>
    @endif
</div>
