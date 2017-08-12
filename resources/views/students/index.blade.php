@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><strong>Students enrolled in {{ $course->name }}</strong></div>
            @if($enrollments->isEmpty())
                @include('students.shared.table.empty')
            @else
                @include('students.shared.table.index')
            @endif
            @if (! $enrollments->isEmpty())
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{ $enrollments->links() }}
                        </div>
                        <div class="col-md-6 text-right pagination-summary">
                            Page {{ $enrollments->currentPage() }} of {{ $enrollments->lastPage() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
