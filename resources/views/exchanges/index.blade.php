@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Exchanges history</div>
        @if ($history->isEmpty())
            @include('exchanges.shared.history.empty')
        @else
            @include('exchanges.shared.history.index', ['exchanges' => $history])
            <div class="row">
                <div class="col">
                    {{ $history->links() }}
                </div>
                <div class="col-auto align-self-center">
                    @if ($history->lastPage() > 0)
                        Page {{ $history->currentPage() }} of {{ $history->lastPage() }}
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
