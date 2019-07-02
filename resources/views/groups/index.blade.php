@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="container">
            <h2 style="display:inline-block">My groups</h2>
            <a href="/groups/create" class="btn btn-primary" style="float:right; display:inline-block">
                    <i class="fas fa-plus"></i> New
            </a>
        </div>
        
        <div class="container">
            @foreach ($confirmedGroups as $cGroup)
                <div class="card" style="margin-top:1em;">
                    <h6 class="card-header">{{ $cGroup->name }}</h6>
                    <div class="card-body">
                        <h6 class="card-title">Group {{ $cGroup->id }}</h6>
                        <p>Total members: <span>{{ $cGroup->confirmedStudents()->count() }}/4</span></p>
                        <p class="card-text" style="color:darkgreen">Accepted at: {{ $cGroup->invitation->accepted_at }}</p>
                        <div class="container" role="group" aria-label="Basic example" style="float:right">
                            <a class="btn btn-success btn-sm" href="/groups/{{ $cGroup->id }}/edit" style="margin-left:1em; float:right">Edit</a>
                            <form method="POST" action="/groups/leave/{{ $cGroup->id }}">
                                {{ method_field('DELETE') }}
                                {{ @csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" style="float:right">Exit</button>
                            </form> 
                        </div>
                    </div>
                </div>
            @endforeach
            <hr>
            @foreach ($pendingGroups as $pGroup)
                <div class="card" style="margin-top:1em;">
                    <h6 class="card-header">{{ $pGroup->name }}</h6>
                    <div class="card-body">
                        <h6 class="card-title">Group {{ $pGroup->id }}</h6>
                        <p>Total members: <span>{{ $pGroup->confirmedStudents()->count() }}/4</span></p>
                        <p class="card-text" style="color:palevioletred">Waiting for answer.</p>
                        <div class="container" role="group" aria-label="Basic example" style="float:right">
                            <form method="POST" action="/groups/accept/{{ $pGroup->id }}">
                                {{ method_field('PATCH') }}
                                {{ @csrf_field() }}
                            <button type="submit" class="btn btn-success btn-sm" style="margin-left:1em;float:right">Accept</button>
                            </form>
                            <form method="POST" action="/groups/refuse/{{ $pGroup->id }}">
                                {{ method_field('DELETE') }}
                                {{ @csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" style="float:right">Refuse</button>
                            </form>  
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>   
    
    
@endsection