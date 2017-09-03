@if($exchanges->isEmpty())
    @include('exchanges.dashboard.proposed.empty')
@else
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Exchanges waiting your confirmation</div>
        <table class="table card-table mb-0 table-responsive">
            <tbody>
                @each('exchanges.dashboard.proposed.show', $exchanges, 'exchange')
            </tbody>
        </table>
    </div>
@endif
