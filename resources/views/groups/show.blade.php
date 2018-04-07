@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    Sistemas Operativos
                </div>
            </div>
        </div>
        @if(0)
            <div align="center" class="card-header">
                <p> You are not a member of any group</p>
                <a href="/groups/store/3" class="btn btn-success btn-sm">Create group</a>
            </div>
        @else
            <div align="center" class="card-header">
                <form method="POST" action="/groups/invite/3"> <!-- add groupId and-->
                    Group Size: 3 - 8 students&emsp;
                    {{ csrf_field() }}
                    <input class="btn" id="number" pattern="[0-9.]+" placeholder="student number" type="text">
                    <input class="btn btn-success btn-sm" type="submit" value="Invite">
                </form>
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
                        <tr>
                            <td>João Vilaça</td>
                            <td>a82339</a></td>
                        </tr>
                        <tr>
                            <td>Pedro Machado</td>
                            <td>a82338</a></td>
                        </tr>
                </tbody>
            </table>
            <div align="center" class="card-header">
                <a href="/groups/leave/3" class="btn btn-info btn-sm">Leave group</a>
            </div>
        @endif
    </div>
@endsection
