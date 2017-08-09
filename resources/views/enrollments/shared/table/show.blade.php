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
        @include('enrollments.shared.action', ['course' => $enrollment->course])
    </td>
</tr>
