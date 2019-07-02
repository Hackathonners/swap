@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Create group</h1>
    </div>

    <div class="container">
        <div class="alert alert-info" role="alert">
            You are not in a group. Wait for invites or create one.
        </div>
        <div class="card-body p-5 text-left border">
            <h6 class="card-title">To create a group, insert one student email to send an invite.</h6>

            <form method="POST" action="/groups">
                {{ @csrf_field() }}
                <div class="field">
                    <label for="select_course">Select a course:</label>
                    <select class="custom-select" id="course_id" name="course_id">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" style="margin-top: 10px">
                    <label for="email">Input the email of a student:</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="number@alunos.uminho.pt" 
                        aria-label="number@alunos.uminho.pt" aria-describedby="basic-addon2" required>
                </div>
                <div class="field" style="margin-top: 10px">
                    <div class="control">
                        <button type="submit" class="btn btn-info">Create group</button>
                    </div>
                </div>
            </form>
    </div>

@endsection