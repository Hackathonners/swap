<tr>
    <td>
        {{ $exchange->course()->name }}
        <br>
        <small class="text-muted">
            From <strong>{{ $exchange->fromShift()->tag }}</strong>
            to <strong>{{ $exchange->toShift()->tag }}</strong>
        </small>
    </td>
    <td class="text-right">
        <form action="{{ route('exchanges.solver.destroy', $exchange) }}" method="post" class="hidden">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-link btn-sm"><span class="text-muted">Delete request</span></button>
        </form>
    </td>
</tr>
