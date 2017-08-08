<tr>
    <td>{{ $enrollment->student->student_number }}</td>
    <td>{{ $enrollment->student->user->name }}</td>
    <td>{{ $enrollment->present()->getShiftTag() }}</td>
    <td>{{ $enrollment->present()->getUpdatedAt() }}</td>
</tr>
