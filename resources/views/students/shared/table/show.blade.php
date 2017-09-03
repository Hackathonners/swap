<tr>
    <td><a href="{{ route('students.show', $enrollment->student_id) }}">{{ $enrollment->student->student_number }}</a></td>
    <td>{{ $enrollment->student->user->name }}</td>
    <td>{{ $enrollment->present()->getShiftTag() }}</td>
    <td>{{ $enrollment->present()->getUpdatedAt() }}</td>
</tr>
