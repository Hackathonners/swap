@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header"><strong>Student</strong> - {{ $student->user->name }}</div>
        <div class="card-body">
            <b-tabs ref="tabs" pills class="nav-fill" card>
                <b-tab title="Enrollments">
                    @include('enrollments.dashboard.summary.index')
                </b-tab>
                <b-tab title="Exchanges">
                    <div class="card card--section">
                        <div class="card-header">Exchanges history</div>
                        @include('exchanges.partials.table.index', ['exchanges' => $historyExchanges])
                    </div>
                </b-tab>
            </b-tabs>
        </div>
    </div>
@endsection
