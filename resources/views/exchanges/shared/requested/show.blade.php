<tr>
    <td>
        <strong>{{ $exchange->course()->name }}</strong>
        <br>
        <small class="text-muted">
            From <strong>{{ $exchange->toShift()->tag }}</strong>
            to <strong>{{ $exchange->fromShift()->tag }}</strong>
            requested by <strong>{{ $exchange->fromStudent()->user->name }} ({{ $exchange->fromStudent()->student_number }})</strong>
        </small>
    </td>
    <td class="text-right">
        <form action="{{ route('exchanges.confirm') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="exchange_id" value="{{ $exchange->id }}">
            <div class="btn-group">
                <button type="submit" class="btn btn-success btn-sm">Confirm change</button>
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#" class="text-danger">Decline change</a></li>
                </ul>
            </div>
        </form>
    </td>
</tr>
