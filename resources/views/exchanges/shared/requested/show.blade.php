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
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button type="submit" class="dropdown-item btn btn-sm text-danger">Decline change</button>
                </div>
            </div>
        </form>
    </td>
</tr>
