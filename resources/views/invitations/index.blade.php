@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
    @if($invitations->isEmpty())
        @include('invitations.empty')
    @else
        <div class="card-header">Invitations</div>
        @foreach ($invitations as $invitation)
            <table class="card-table table">
                <tbody>
                    <tr>
                        <th colspan="2" class="table-active">
                            Members
                        </th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Number</th>
                    </tr>
                    @foreach ($invitation->students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->student_number }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div align="center" class="card-header">
                <a href="/groups/invitations/{{ $invitation->id }}/accept" class="btn btn-primary btn-sm">Accept Invitation</a>
                <a href="/groups/invitations/{{ $invitation->id }}/destroy" class="btn btn-danger btn-sm">Delete Invitation</a>
            </div>
            </br></br></br>
        @endforeach
    @endif
    </div>
@endsection