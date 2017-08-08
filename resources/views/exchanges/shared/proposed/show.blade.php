<tr>
    <td>
        <strong>{{ $exchange->course()->name }}</strong>
        <br>
        <small class="text-muted">
            From <strong>{{ $exchange->fromShift()->tag }}</strong>
            to <strong>{{ $exchange->toShift()->tag }}</strong>
            requested to <strong>{{ $exchange->toStudent()->user->name }} ({{ $exchange->toStudent()->student_number }})</strong>
        </small>
    </td>
    <td class="text-right">
        <form action="{{ route('exchanges.confirm') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="exchange_id" value="{{ $exchange->id }}">
            <button type="submit" class="btn btn-link btn-sm"><span class="text-muted">Delete request</span></button>
        </form>
    </td>
</tr>
