@if(! $exchanges->isEmpty())
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Exchanges waiting your confirmation</div>
        <div class="card-table table-responsive">
            <table class="table mb-0">
                <tbody>
                    @each('exchanges.dashboard.proposed.show', $exchanges, 'exchange')
                </tbody>
            </table>
        </div>
    </div>
@endif
