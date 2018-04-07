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
            <div align="center" class="card-header">
                <form method="POST" action="/groups/size"> <!-- add courseId -->
                    {{ csrf_field() }}
                    <input id="min" type="number" placeholder="min" min="0" max="20">
                    /
                    <input id="max" type="number" placeholder="max" min="0" max="20">
                    <input class="btn btn-success btn-sm" type="submit" value="Set Size">
                </form>
            </div>
    </div>
@endsection
