@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
        <div class="card-header">Groups</div>
        <table class="card-table table table-responsive">
            <tbody>
                <tr>
                    <th>Course</th>
                    <th>Status</th>
                    <th style="text-align:center">Invitations Pending</th>
                    <th style="text-align:center">Details</th>
                </tr>
                @foreach ($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->name }}</td>
                        @if ($enrollment->group_status)
                            <td>{{ $enrollment->group_status }} out of {{ $enrollment->group_max }} members</td>
                        @elseif ($enrollment->group_min != $enrollment->group_max)
                            <td>Not a member of any group. (Group Size: {{ $enrollment->group_min }} to {{ $enrollment->group_max }})</td>
                        @else
                            <td>Not a member of any group. (Group Size: {{ $enrollment->group_min }})</td>
                        @endif
                        <td style="text-align:center">{{ $enrollment->number_invitations }}</td>
                        <td style="text-align:center"><a href="groups/{{ $enrollment->course_id }}" /a> &#x21E8;</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection