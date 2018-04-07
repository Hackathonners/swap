@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    Groups
                </div>
            </div>
        </div>
        <table class="card-table table">
            <tbody>
                    <tr>
                        <th colspan="3" class="table-active">
                            Second Year
                        </th>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <th>Course</th>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Sistemas Operativos</a></td>
                        <td><a href="/groups/show/3" class="btn btn-success btn-sm">See group</a> <!-- add course id --></td>
                    </tr>

                    <tr>
                        <th colspan="3" class="table-active">
                            Third Year
                        </th>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <th>Course</th>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Cálculo de Programas</a></td>
                        <td><a href="/groups/show/3" class="btn btn-success btn-sm">See group</a> <!-- add course id --></td>
                    </tr>
            </tbody>
        </table>
    </div>
@endsection
