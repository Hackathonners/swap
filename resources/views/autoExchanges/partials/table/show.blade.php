<tr>
    <td>
        {{ $exchange->fromStudent()->user->name }} <small>({{ $exchange->fromStudent()->student_number }})</small>
        <br>
        <small class="text-muted">
            <strong>{{ $exchange->fromShift()->tag }}</strong> on <strong>{{ $exchange->course()->name }}</strong>
        </small>
    </td>
    <td class="text-muted text-center">
        <h5 class="m-0 p-0">&#8646;</h5>
        <small>{{ $exchange->getDate()->toDayDateTimeString() }}</small>
    </td>
    <td>
        <br>
        <small class="text-muted">
            <strong>{{ $exchange->toShift()->tag }}</strong> on <strong>{{ $exchange->course()->name }}</strong>
        </small>
    </td>
</tr>
