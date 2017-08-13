@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><strong>Settings</strong></div>
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('settings.update') }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <label>Period of enrollments</label>
                            <calendar-enrollments></calendar-enrollments>
                            @if (str_contains($errors->first('enrollments_end_at'), 'required'))
                                <div class="form-text text-danger">The enrollments period field is required.</div>
                            @else
                                <div class="form-text text-danger">{{ $errors->first('enrollments_start_at') }}</div>
                                <div class="form-text text-danger">{{ $errors->first('enrollments_end_at') }}</div>
                            @endif
                        </div>

                        <div class="form-group{{ ( $errors->has('exchanges_start_at') || $errors->has('exchanges_end_at') ) ? ' has-danger' : '' }}">
                            <label>Period of exchanges</label>
                            <calendar-exchanges></calendar-exchanges>
                            @if (str_contains($errors->first('exchanges_end_at'), 'required'))
                                <div class="form-text text-danger">The exchanges period field is required.</div>
                            @else
                                <div class="form-text text-danger">{{ $errors->first('exchanges_start_at') }}</div>
                                <div class="form-text text-danger">{{ $errors->first('exchanges_end_at') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
