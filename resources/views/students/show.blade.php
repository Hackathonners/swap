@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header"><strong>Student</strong> - {{ $student->user->name }}</div>
        <div class="card-body">
            <b-tabs ref="tabs" pills class="nav-fill">
                <b-tab title="Current enrollments">
                    @include('enrollments.dashboard.summary.index')
                </b-tab>
                <b-tab title="Exchanges history">
                    @include('exchanges.partials.table.index', ['exchanges' => $historyExchanges])
                </b-tab>
            </b-tabs>
        </div>
    </div>
@endsection
