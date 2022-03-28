<tr>
    <td class="exchange-icon d-none d-sm-table-cell"><img alt="switch" height="24" src="{{ asset('images/switch-vertical.svg') }}"></td>
    <td>
        {{ $exchange->course()->name }}
        <br>
        <small class="text-muted">
            From <strong>{{ $exchange->fromShift()->tag }}</strong>
            to <strong>{{ $exchange->toShift()->tag }}</strong>
            {{-- requested to <strong>{{ $exchange->toStudent()->user->name }} ({{ $exchange->toStudent()->student_number }})</strong> --}}
        </small>
    </td>
    <td class="text-right">
        <button v-delete-exchange="{{ $exchange }}" type="button" class="btn btn-link btn-sm"><span class="text-muted">Delete request</span></button>
    </td>
</tr>
