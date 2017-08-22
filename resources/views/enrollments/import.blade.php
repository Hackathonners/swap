@extends('layouts.app')

@section('content')
    <div class="card card--section mb-4">
        <div class="card-header">Import enrollments</div>
        <div class="card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('enrollments.import') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                {{-- File input --}}
                <div class="form-group">
                    <label for="enrollments">Enrollments file</label>
                    <input id="enrollments" type="file" class="form-control {{ $errors->has('enrollments') ? 'is-invalid' : '' }}" name="enrollments" required autofocus accept=".csv">
                    @if ($errors->has('enrollments'))
                        <div class="invalid-feedback">{{ $errors->first('enrollments') }}</div>
                    @else
                        <small class="form-text text-muted">The accepted file format is CSV.</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Import enrollments</button>
            </form>
        </div>
    </div>
@endsection
