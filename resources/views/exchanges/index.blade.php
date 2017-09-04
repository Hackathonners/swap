@extends('layouts.app')

@section('content')
    @include('exchanges.partials.table.index', ['exchanges' => $history, 'title' => 'Exchanges history'])
@endsection
