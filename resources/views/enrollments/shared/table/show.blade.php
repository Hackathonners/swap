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
                <button type="button" class="btn btn-default btn-sm">Change shift</button>
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="#" class="text-danger">Delete enrollment</a></li>
                </ul>
            </div>
        @endif
    </td>
</tr>
