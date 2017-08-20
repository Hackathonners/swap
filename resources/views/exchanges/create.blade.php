@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><strong>Exchange shift</strong></div>
            <div class="card-body">
                <form method="post" action="{{ route('exchanges.store') }}">
                    {{ csrf_field() }}

                    {{-- From enrollment--}}
                    <div class="form-group">
                        <label>From enrollment</label>
                        <input type="hidden" name="from_enrollment_id" value="{{ $enrollment->id }}">
                        <input type="text" class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}" value="{{ $enrollment->present()->inlineToString() }}" required readonly>
                        <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                    </div>

                    {{-- To enrollment--}}
                    <div class="form-group">
                        <label>To enrollment</label>
                        <span><enrollment-select name="to_enrollment_id" :options="{{ $matchingEnrollments }}"></v-select></span>
                        <div class="form-text text-danger">{{ $errors->first('to_enrollment_id') }}</div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary">Request exchange</button>
                </form>
            </div>
        </div>
    </div>
@endsection
