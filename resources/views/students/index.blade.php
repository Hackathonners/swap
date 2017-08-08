@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Students enrolled in <strong>{{ $course->name }}</strong></div>
                <div class="panel-body">
                    @if($enrollments->isEmpty())
                        @include('students.shared.table.empty')
                    @else
                        @include('students.shared.table.index')
                    @endif
                </div>
                @if (! $enrollments->isEmpty())
                    <div class="panel-footer">
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
    </div>
</div>
@endsection
