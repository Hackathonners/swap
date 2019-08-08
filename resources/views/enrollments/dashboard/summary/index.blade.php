<div class="card card--section">
    @if($enrollments->isEmpty())
        @include('enrollments.dashboard.summary.empty')
    @else
        <div class="card-header">Current enrollments summary</div>
        <div class="card-table table-responsive">
            <table class="table">
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
                        @each('enrollments.dashboard.summary.show', $enrollments, 'enrollment')
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
