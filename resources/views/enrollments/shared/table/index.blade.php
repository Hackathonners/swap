<table class="card-table table">
    <tbody>
        @foreach ($enrollments as $year => $enrollments)
            <th colspan="4" class="table-active">
                {{ $year }} year
            </th>
            <tr>
                <th class="d-none d-sm-table-cell">Semester</th>
                <th>Course</th>
                <th>Shift</th>
                <th></th>
            </tr>
            @each('enrollments.shared.table.show', $enrollments, 'enrollment')
        @endforeach
    </tbody>
</table>
