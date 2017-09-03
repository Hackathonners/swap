@if($exchanges->isEmpty())
    @include('exchanges.dashboard.requested.empty')
@else
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Pending requested exchanges</div>
        <table class="table card-table mb-0 table-responsive">
            <tbody>
                @each('exchanges.dashboard.requested.show', $exchanges, 'exchange')
            </tbody>
        </table>
    </div>
@endif
