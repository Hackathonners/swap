<div class="card card--section">
    @if($enrollments->isEmpty())
        @include('enrollments.dashboard.summary.empty')
    @else
        <div class="card-header">{{ $title ?? '' }}</div>
        <table class="card-table table table-responsive">
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
    @endif
</div>
