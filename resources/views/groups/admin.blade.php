@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            <h2 style="display:inline-block">Confirmed groups</h2>
            @if(!$groups->count())
                <div class="alert alert-info" role="alert">
                    There are no formed groups yet.
                </div>
            @endif
            <a href="{{ route('groups.export') }}" class="btn btn-primary" style="float:right; display:inline-block">
                <i class="fas fa-file-export"></i> Export
            </a>
        </div>
        <div class="container">
            <div class="card text-center">
                <ul class="list-group">
                    @foreach ($courses as $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $course->name }}
                            <span class="badge badge-primary badge-pill">
                                {{ $course->groups()->count() }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
    </div>
@endsection