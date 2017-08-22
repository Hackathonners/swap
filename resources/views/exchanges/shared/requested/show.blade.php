<tr>
    <td>
        {{ $exchange->course()->name }}
        <br>
        <small class="text-muted">
            From <strong>{{ $exchange->toShift()->tag }}</strong>
            to <strong>{{ $exchange->fromShift()->tag }}</strong>
            requested by <strong>{{ $exchange->fromStudent()->user->name }} ({{ $exchange->fromStudent()->student_number }})</strong>
        </small>
    </td>
    <td class="text-right">
            <div class="btn-group">
                <button v-confirm-exchange="{{ $exchange }}" type="button" class="btn btn-success btn-sm">Confirm change</button>
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <button v-decline-exchange="{{ $exchange }}" type="button" class="dropdown-item btn btn-sm text-danger">Decline change</button>
                </div>
            </div>
        </form>
    </td>
</tr>
