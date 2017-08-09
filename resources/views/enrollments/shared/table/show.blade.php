<tr>
    <td>
        {{ $enrollment->course->present()->getOrdinalSemester() }}
    </td>
    <td>
        {{ $enrollment->course->name }}
    </td>
    <td>
        {{ $enrollment->present()->getShiftTag() }}
    </td>
    <td class="text-right">
        @if (!is_null($enrollment->shift))
            <div class="btn-group">
                <button type="button" class="btn btn-secondary btn-sm">Exchange shift</button>
                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item btn btn-sm text-danger">Delete enrollment</button>
                </ul>
            </div>
        @endif
    </td>
</tr>
