@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ $course->name }}
                </div>
            </div>
        </div>
        @if (!$membership)
            <div align="center" class="card-header">
                <p> You are not a member of any group</p>
                <a href="/groups/{{ $course->id }}/store" class="btn btn-success btn-sm">Create group</a>
                @if ($course->number_invitations)
                    <a href="/groups/{{ $course->id }}/invitations" class="btn btn-primary btn-sm">View invitations ({{ $course->number_invitations }})</a>
                @endif
            </div>
        @else
            <div style="text-align:center" class="card-header">
                <div style="display:inline-flex">
                    @if ($course->group_min == $course->group_max)
                        Group Size: {{ $course->group_min }} students&emsp;
                    @else
                        Group Size: {{ $course->group_min }} to {{ $course->group_max }} students&emsp;
                    @endif
                    @if (count($membership->group->memberships) < $course->group_max)
                        <form method="POST" action="/groups/{{ $membership->group->id }}/invitations/{{ $course->id }}/store">
                            {{ csrf_field() }}
                            <input class="btn" name="student_number" pattern="a[0-9.]{5-6}" placeholder="Student Number" type="text">
                            <input class="btn btn-success" type="submit" value="Invite">
                        </form>
                    @endif
                </div>
            </div>
            <table class="card-table table">
                <tbody>
                    <tr>
                        <th colspan="2" class="table-active">
                            Students
                        </th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Number</th>
                    </tr>
                        @foreach ($membership->group->memberships as $member)
                            <tr>
                                <td>{{ $member->student->user->name }}</td>
                                <td>{{ $member->student->student_number }}</a></td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
            <div align="center" class="card-header">
                <a href="/groups/{{ $course->id }}/destroy" class="btn btn-info btn-sm">Leave group</a>
                @if ($course->number_invitations)
                    <a href="/groups/{{ $course->id }}/invitations" class="btn btn-primary btn-sm">View invitations ({{ $course->number_invitations }})</a>
                @endif
            </div>
        @endif
    </div>
@endsection
