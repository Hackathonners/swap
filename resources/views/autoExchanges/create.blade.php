@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Propose a new shift exchange</div>
        <div class="card-body">
            <form method="post" action="{{ route('autoExchanges.store', $enrollment->id) }}">
                {{ csrf_field() }}

                {{-- From enrollment--}}
                <div class="form-group">
                    <label>From enrollment</label>
                    <input type="text"
                    class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}"
                    value="{{ $enrollment->present()->inlineToString() }}"
                    required readonly>
                    <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                </div>

                {{-- To shift--}}
                <div class="form-group">
                    <label>To Shift <label>
                    <shift-select name="to_shift_tag" :options="{{ $matchingShifts}}"></shift-select>
                    <div class="form-text text-danger">{{ $errors->first('to_shift_tag') }}</div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Request exchange</button>
            </form>
        </div>
    </div>
@endsection
