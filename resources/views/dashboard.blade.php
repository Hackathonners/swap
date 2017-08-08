@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if(!$proposedExchanges->isEmpty())
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading"><strong>Exchanges waiting for your confirmation</strong></div>
                    <div class="panel-table">
                        @include('exchanges.shared.requested.index', ['exchanges' => $proposedExchanges])
                    </div>
                </div>
            </div>
        @endif
        @if(!$requestedExchanges->isEmpty())
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Pending requested exchanges</strong></div>
                    <div class="panel-table">
                        @include('exchanges.shared.proposed.index', ['exchanges' => $requestedExchanges])
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Current enrollments</strong></div>
                <div class="panel-table">
                    @if ($enrollments->isEmpty())
                        @include('enrollments.shared.table.empty')
                    @else
                        @include('enrollments.shared.table.index')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
