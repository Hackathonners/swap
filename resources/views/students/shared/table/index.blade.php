<div class="card-table table-responsive">
    <table class="table">
        <tbody>
            <tr>
                <td><strong>Student ID</strong></td>
                <td><strong>Name</strong></td>
                <td><strong>Shift</strong></td>
                <td><strong>Enrolled at</strong></td>
            </tr>
            @each('students.shared.table.show', $enrollments, 'enrollment')
        </tbody>
    </table>
</div>
