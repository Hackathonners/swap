<tr>
    <td>{{ $enrollment->student->student_number }}</td>
    <td><a href="{{ route('students.show', $enrollment->student_id) }}">{{ $enrollment->student->user->name }}</a></td>
    <td>{{ $enrollment->present()->getShiftTag() }}</td>
    <td>{{ $enrollment->present()->getUpdatedAt() }}</td>
</tr>
