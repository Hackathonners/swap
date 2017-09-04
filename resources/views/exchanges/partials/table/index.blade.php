<div class="card card--section">
    @if($exchanges->isEmpty())
        @include('exchanges.partials.table.empty')
    @else
    <div class="card-header">{{ $title ?? '' }}</div>
        <table class="card-table table table-responsive">
            <tbody>
                @each('exchanges.partials.table.show', $exchanges, 'exchange')
            </tbody>
        </table>
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
    </div>
    @endif
</div>
