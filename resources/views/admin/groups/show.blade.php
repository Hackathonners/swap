@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
        @if (count($groupsUsers) == 0)
            @include('admin.groups.empty')
        @else
            <div class="card-header">Groups</div>
                <table class="card-table table table-responsive">
                    <tbody>
                        @foreach ($groupsUsers as $users)
                            <tr>
                                <th colspan="3" class="table-active">
                                </th>
                            </tr>
                            <tr>
                                <th class="d-none d-sm-table-cell">Name</th>
                                <th>Number</th>
                            </tr>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->student_number }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
