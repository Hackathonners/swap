@if(! $exchanges->isEmpty())
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Pending automatic exchanges</div>
        <table class="table card-table mb-0 table-responsive">
            <tbody>
                @each('exchanges.dashboard.bags.show', $exchanges, 'exchange')
            </tbody>
        </table>
    </div>
@endif
