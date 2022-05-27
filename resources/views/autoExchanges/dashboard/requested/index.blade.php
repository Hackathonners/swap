@if(! $exchanges->isEmpty())
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Pending requested exchanges</div>
        <div class="card-table table-responsive">
            <table class="table mb-0">
                <tbody>
                    @each('autoExchanges.dashboard.requested.show', $exchanges, 'exchange')
                </tbody>
            </table>
        </div>
    </div>
@endif
