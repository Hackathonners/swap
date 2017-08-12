@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Import File</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('enrollments.import') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group {{ $errors->has('enrollments') ? ' has-error' : '' }}">
                                    <label for="enrollments-input">
                                        Please select the enrollments file to upload.
                                    </label>

                                    <div class="input-group">
                                        <label class="input-group-btn">
                                            <span class="btn btn-default">
                                                Browse&hellip; <input id="enrollments-input" type="file" class="form-control hidden" name="enrollments" aria-describedby="enrollments-import-help" required>
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" readonly>
                                    </div>

                                    <span class="help-block" id="enrollments-input-help">
                                        @if ($errors->has('enrollments'))
                                            <strong>{{ $errors->first('enrollments') }}</strong>
                                        @else
                                            Please select a valid <strong>CSV</strong> file.
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button id="submit" type="submit" class="btn btn-primary" disabled>
                                    Import
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
