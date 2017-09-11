<div class="btn-group">
    {{-- Exchange action --}}
    @if ($settings->withinExchangePeriod() && $enrollment->availableForExchange())
        <a
            href="{{ route('exchanges.create', $enrollment->id )}}"
            class="btn btn-outline-secondary btn-sm">
            Exchange shift
        </a>
    @elseif (! $settings->withinEnrollmentPeriod())
    {{-- Enrollment actions --}}
        <button type="button" class="btn btn-outline-secondary btn-sm disabled">Enrolled</button>
    @endif
</div>
