<!DOCTYPE html>
<html>
<table>
	<tr>
		<th>Course</th>
		<th>Student ID</th>
		<th>Student Name</th>
		<th>Student E-mail</th>
		<th>Enrollment Date</th>
	</tr>
	@foreach($enrollments as $enrollment)
	<tr>
		<td>{{ $enrollment->course->name }}</td>
		<td>{{ $enrollment->student->student_number }}</td>
		<td>{{ $enrollment->student->user->name }}</td>
		<td>{{ $enrollment->student->user->email }}</td>
		<td>{{ $enrollment->student->created_at }}</td>
	</tr>
	@endforeach
</table>
</html>
