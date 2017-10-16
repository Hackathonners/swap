@if(! $exchanges->isEmpty())
    <div class="card card--section mb-5">
        <div class="card-header highlight-warning">Pending requested exchanges</div>
                <b-tabs pills card class="nav-fill">
                    <b-tab title="Direct exchange">
                        @if(! $exchanges->isEmpty())
                            <table class="table card-table mb-0 table-responsive">
                                    @each('exchanges.dashboard.requested.show', $exchanges, 'exchange')
                            </table>
                        @else
                            @include('exchanges.dashboard.request.empty')
                        @endif
                    </b-tab>
                    <b-tab title="Enqueued exchange">
                        @if(! $exchanges->isEmpty())
                            <table class="table card-table mb-0 table-responsive">
                                <tbody>
                                    @each('exchanges.dashboard.requested.show', $exchanges, 'exchange')
                                </tbody>
                            </table>
                        @else
                            @include('exchanges.dashboard.request.empty')
                        @endif
                    </b-tab>
                <b-tabs>
    </div>
@endif
