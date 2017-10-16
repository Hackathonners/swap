@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Propose a new shift exchange</div>
        <div class="card-body">
            <form method="post" action="{{ route('exchanges.store', $enrollment->id) }}">
                {{ csrf_field() }}
            <label>Choose an exchange method:</label>
                <b-tabs pills card lazy class="nav-fill">
                  <b-tab title="Direct exchange">

                      {{-- From course --}}
                     <div class="form-group">
                         <label>From course</label>
                         <input type="text"
                         class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}"
                         value="{{ $enrollment->course->name }}"
                         required readonly>
                         <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                     </div>

                      {{-- From shift --}}
                     <div class="form-group">
                         <label>From Shift</label>
                         <input type="text"
                         class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}"
                         value="{{ $enrollment->shift->tag}}"
                         required readonly>
                         <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                     </div>


                     {{-- To enrollment--}}
                     <div class="form-group">
                         <label>To enrollment</label>
                         <enrollment-select name="to_enrollment_id" :options="{{ $matchingEnrollments }}"></enrollment-select>
                         <div class="form-text text-danger">{{ $errors->first('to_enrollment_id') }}</div>
                     </div>
                  </b-tab>

                  <b-tab title="Enqueued exchange">

                      {{-- From course --}}
                     <div class="form-group">
                         <label>From course</label>
                         <input type="text"
                         class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}"
                         value="{{ $enrollment->course->name }}"
                         required readonly>
                         <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                     </div>

                      {{-- From shift --}}
                     <div class="form-group">
                         <label>From Shift</label>
                         <input type="text"
                         class="form-control {{ $errors->has('from_enrollment_id') ? 'is-invalid' : '' }}"
                         value="{{ $enrollment->shift->tag}}"
                         required readonly>
                         <div class="form-text text-danger">{{ $errors->first('from_enrollment_id') }}</div>
                     </div>

                     {{-- To shift--}}
                     <div class="form-group">
                         <label>To shift</label>
                         <shift-select name="to_shift_id" :options="{{ $shiftsAvailable }}"></shift-select>
                         <div class="form-text text-danger">{{ $errors->first('to_shift_id') }}</div>
                     </div>
                  </b-tab>
                </b-tabs>


                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Request exchange</button>
            </form>
        </div>
    </div>
@endsection
