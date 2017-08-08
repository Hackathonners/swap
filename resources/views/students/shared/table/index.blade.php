<table class="table">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Shift</th>
            <th>Enrolled at</th>
        </tr>
    </thead>
    <tbody>
        @each('students.shared.table.show', $enrollments, 'enrollment')
    </tbody>
</table>
