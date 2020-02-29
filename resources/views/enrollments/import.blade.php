@extends('layouts.app')

@section('content')
    <div class="card card--section mb-5">
        <div class="card-header">Import enrollments</div>
        <div class="card-body">

            <form class="form-horizontal" role="form" method="POST"
                action="{{ route('enrollments.import') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}

                {{-- File input --}}
                <div class="form-group">
                    <label for="enrollments">Enrollments file</label>
                    <file-input name="enrollments" id="enrollments" file-types=".csv" state="{{ $errors->has('enrollments') ? 'invalid' : 'null' }}" ></file-input>
                    @if ($errors->has('enrollments'))
                        <div class="form-text text-danger">{{ $errors->first('enrollments') }}</div>
                    @else
                        <small class="form-text text-muted">The accepted file format is CSV.</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Import enrollments</button>
            </form>
        </div>
    </div>
@endsection
