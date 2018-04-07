@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Edit settings</div>
        <div class="card-body">
            <form role="form" method="POST" action="{{ route('settings.update') }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <div class="form-group">
                    <label>Period of enrollments</label>
                    <calendar-enrollments :date="['{{ old('enrollments_start_at') ?: $settings->enrollments_start_at }}', '{{ old('enrollments_end_at') ?: $settings->enrollments_end_at }}']"></calendar-enrollments>
                    @if (str_contains($errors->first('enrollments_end_at'), 'required'))
                        <div class="form-text text-danger">The enrollments period field is required.</div>
                    @else
                        <div class="form-text text-danger">{{ $errors->first('enrollments_start_at') }}</div>
                        <div class="form-text text-danger">{{ $errors->first('enrollments_end_at') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Period of exchanges</label>
                    <calendar-exchanges :date="['{{ old('exchanges_start_at') ?: $settings->exchanges_start_at }}', '{{ old('exchanges_end_at') ?: $settings->exchanges_end_at }}']"></calendar-exchanges>
                    @if (str_contains($errors->first('exchanges_end_at'), 'required'))
                        <div class="form-text text-danger">The exchanges period field is required.</div>
                    @else
                        <div class="form-text text-danger">{{ $errors->first('exchanges_start_at') }}</div>
                        <div class="form-text text-danger">{{ $errors->first('exchanges_end_at') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Period of groups enrollments</label>
                    <calendar-exchanges :date="['{{ old('group_enrollments_start_at') ?: $settings->group_enrollments_start_at }}', '{{ old('group_enrollments_end_at') ?: $settings->group_enrollments_end_at }}']"></calendar-exchanges>
                    @if (str_contains($errors->first('exchanges_end_at'), 'required'))
                        <div class="form-text text-danger">The group enrollment period field is required.</div>
                    @else
                        <div class="form-text text-danger">{{ $errors->first('group_enrollments_start_at') }}</div>
                        <div class="form-text text-danger">{{ $errors->first('group_enrollments_end_at') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">
                    Update settings
                </button>
            </form>
        </div>
    </div>
@endsection
