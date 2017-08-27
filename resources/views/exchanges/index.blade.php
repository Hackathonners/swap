@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Exchanges history</div>
        @include('exchanges.partials.table.index', ['exchanges' => $history])
    </div>
@endsection
