@if ($exchanges->isEmpty())
    @include('autoExchanges.partials.table.empty')
@else
    <div class="card-table table-responsive">
        <table class="table">
            <tbody>
                @each('autoExchanges.partials.table.show', $exchanges, 'exchange')
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col">
            {{ $exchanges->render() }}
        </div>
        <div class="col-auto align-self-center">
            @if ($exchanges->lastPage() > 0)
                Page {{ $exchanges->currentPage() }} of {{ $exchanges->lastPage() }}
            @endif
        </div>
    </div>
@endif
