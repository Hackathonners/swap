@extends('layouts.app')

@section('content')
    @if(! auth()->user()->verified)
        <div class="alert alert-warning">
            Your account is unverified. Please check your student email for the confirmation link.
        </div>
    @endif

    {{-- Exchanges waiting confirmation --}}
    {{-- @includeWhen(
        $settings->withinExchangePeriod(),
        'exchanges.dashboard.proposed.index',
        ['exchanges' => $proposedExchanges]
    ) --}}

    {{-- Pending requested exchanges --}}
    @includeWhen(
        $settings->withinExchangePeriod(),
        'exchanges.dashboard.requested.index',
        ['exchanges' => $requestedExchanges]
    )

    {{-- Enrollments summary --}}
    @include('enrollments.dashboard.summary.index')

    <confirm-exchange-modal></confirm-exchange-modal>
    <decline-exchange-modal></decline-exchange-modal>
    <delete-exchange-modal></delete-exchange-modal>
@endsection
