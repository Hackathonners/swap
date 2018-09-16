@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
        @if (count($groups) == 0)
            @include('admin.groups.empty')
        @else
            <div class="card-header">Groups</div>
            <table class="card-table table table-responsive">
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <th colspan="3" class="table-active">
                                Group
                            </th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                        </tr>
                        @foreach ($group->memberships as $membership)
                            <tr>
                                <td class="d-none d-sm-table-cell">{{ $membership->student->user->name }}</td>
                                <td>{{ $membership->student->student_number }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
