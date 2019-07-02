@extends('layouts.app')

@section('content')
    
    <div class="container">

        <div class="container" style="margin-bottom:2em">
            <h1>Edit group {{ $group->id }}</h1>
            <h4 style="display:inline-block">{{ $group->course()->first()->name }}</h4>
            <button {{ $group->students()->count() == 4 ? 'disabled' : '' }} type="button" class="btn btn-success"
            data-toggle="modal" data-target="#exampleModal" style="display:inline-block; float:right">
                Add <span class="badge badge-light">{{ $group->confirmedStudents()->count() }}/4</span>
            </button>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Send invite</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="POST" action="/groups/sendInvite/{{ $group->id }}">
                            {{ @csrf_field() }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Send invite</label>
                                    <input type="email" class="form-control" id="email1" name="email" aria-describedby="emailHelp" placeholder="number@alunos.uminho.pt">
                                    <small id="emailHelp" class="form-text text-muted">Add a new student to the group.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="container">
            <ul class="list-group list-group-flush">
                @foreach ($group->confirmedStudents()->get() as $cStudent)
                    <li class="list-group-item">
                        <i class="fas fa-check-circle" style="color:darkgreen"></i>
                        <b>Name</b>: {{ $cStudent->user()->first()->name }}
                            |   
                        <b>Email</b>: {{ $cStudent->user()->first()->email }}
                    </li>
                @endforeach
                <hr>
                @foreach ($group->pendingStudents()->get() as $pStudent)
                    <li class="list-group-item">
                        <i class="fas fa-question-circle" style="color:gold"></i>
                        <b>Name</b>: {{ $pStudent->user()->first()->name }}
                            |   
                        <b>Email</b>: {{ $pStudent->user()->first()->email }}
                    </li>
                @endforeach
            </ul>
            <a href="/groups" class="btn btn-primary" style="float:right; margin-top:1em">Save</a>
        </div>
 
    </div>

@endsection