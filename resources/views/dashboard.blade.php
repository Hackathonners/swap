@extends('layouts.app')

@section('content')
    @if(!auth()->user()->verified)
        <div class="alert alert-warning">
            Your account is unverified. Please check your student email for the confirmation link.
        </div>
    @endif
    @if(!$proposedExchanges->isEmpty() && $settings->withinExchangePeriod())
        <div class="card card--section mb-5">
            <div class="card-header highlight-warning">Exchanges waiting your confirmation</div>
            @include('exchanges.shared.requested.index', ['exchanges' => $proposedExchanges])
        </div>
    @endif
    @if(!$requestedExchanges->isEmpty() && $settings->withinExchangePeriod())
        <div class="card card--section mb-5">
            <div class="card-header">Pending requested exchanges</div>
            @include('exchanges.shared.proposed.index', ['exchanges' => $requestedExchanges])
        </div>
    @endif
    @if ($enrollments->isEmpty())
        @include('enrollments.shared.table.empty')
    @else
        <div class="card card--section mb-5">
            <div class="card-header">Current enrollments summary</div>
            @include('enrollments.shared.table.index')
        </div>
    @endif

    <confirm-exchange-modal></confirm-exchange-modal>
    <decline-exchange-modal></decline-exchange-modal>
    <delete-exchange-modal></delete-exchange-modal>
@endsection
