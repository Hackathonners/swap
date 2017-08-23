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
                @if ($enrollments->lastPage() > 0)
                    Page {{ $enrollments->currentPage() }} of {{ $enrollments->lastPage() }}
                @endif
            </div>
        </div>
    </div>
@endsection
