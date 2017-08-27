<tr>
    <td class="d-none d-sm-table-cell">
        {{ $enrollment->course->present()->getOrdinalSemester() }}
    </td>
    <td>
        {{ $enrollment->course->name }}
    </td>
    <td>
        {{ $enrollment->present()->getShiftTag() }}
    </td>
    <td class="text-right">
        @include('enrollments.dashboard.summary.action')
    </td>
</tr>
