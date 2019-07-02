<!DOCTYPE html>
<html>
<table>
    <tr>
        <th>Group ID</th>
        <th>Course</th>
        <th>Course Name</th>
        <th>Student</th>
    </tr>
    @foreach($groups as $group)
        @foreach ($group->students as $student)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->course['code'] }}</td>
                <td>{{ $group->course['name'] }}</td>
                <td>{{ $student->student_number }}</td>
            </tr>
        @endforeach   
    @endforeach
</table>
</html>