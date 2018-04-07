@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    Groups Size
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
                        <th>Size</th>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Cálculo de Programas</a></td>
                        <td>
                            <form align=left method="POST" action="/groups/size"> <!-- add courseId -->
                                {{ csrf_field() }}
                                <input id="min" type="number" placeholder="min" min="0" max="20">
                                /
                                <input id="max" type="number" placeholder="max" min="0" max="20">
                                <input class="btn btn-success btn-sm" type="submit" value="Set Size">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Cálculo de Programas</a></td>
                        <td>
                            <form align=left method="POST" action="/groups/size"> <!-- add courseId -->
                                {{ csrf_field() }}
                                <input id="min" type="number" placeholder="min" min="0" max="20">
                                /
                                <input id="max" type="number" placeholder="max" min="0" max="20">
                                <input class="btn btn-success btn-sm" type="submit" value="Set Size">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3" class="table-active">
                            Second Year
                        </th>
                    </tr>
                    <tr>
                        <th>Semester</th>
                        <th>Course</th>
                        <th>Size</th>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Cálculo de Programas</a></td>
                        <td>
                            <form align=left method="POST" action="/groups/size"> <!-- add courseId -->
                                {{ csrf_field() }}
                                <input id="min" type="number" placeholder="min" min="0" max="20">
                                /
                                <input id="max" type="number" placeholder="max" min="0" max="20">
                                <input class="btn btn-success btn-sm" type="submit" value="Set Size">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>2nd</td>
                        <td>Cálculo de Programas</a></td>
                        <td>
                            <form align=left method="POST" action="/groups/size"> <!-- add courseId -->
                                {{ csrf_field() }}
                                <input id="min" type="number" placeholder="min" min="0" max="20">
                                /
                                <input id="max" type="number" placeholder="max" min="0" max="20">
                                <input class="btn btn-success btn-sm" type="submit" value="Set Size">
                            </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
@endsection
