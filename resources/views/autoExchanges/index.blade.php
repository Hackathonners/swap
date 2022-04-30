@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">autoExchanges history</div>
        @include('autoExchanges.partials.table.index', ['exchanges' => $history])
    </div>
@endsection
