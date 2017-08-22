@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Students enrolled in {{ $course->name }}</div>
        @if($enrollments->isEmpty())
            @include('students.shared.table.empty')
        @else
            @include('students.shared.table.index')
        @endif
        <div class="row">
            <div class="col">
                {{ $enrollments->links() }}
            </div>
            <div class="col-auto align-self-center">
                Page {{ $enrollments->currentPage() }} of {{ $enrollments->lastPage() }}
            </div>
        </div>
    </div>
@endsection
