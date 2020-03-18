@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Create a new account</div>
        <div class="card-body">
            <form role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                {{-- Name --}}
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>

                {{-- E-mail address --}}
                <div class="form-group">
                    <label for="student_number">E-mail address</label>
                    <div class="input-group {{ $errors->has('student_number') ? 'is-invalid' : '' }}">
                        <input id="student_number" type="text" class="form-control {{ $errors->has('student_number') ? 'is-invalid' : '' }}" name="student_number" value="{{ old('student_number') }}" required>
                        <span class="input-group-addon">{{ '@'.config('app.mail_domain') }}</span>
                    </div>
                    @if ($errors->has('student_number'))
                        <div class="invalid-feedback">{{ $errors->first('student_number') }}</div>
                    @else
                        <small class="form-text text-muted">Notifications will be sent to your academic e-mail address.</small>
                    @endif
                </div>

                {{-- Password --}}
                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" required>
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                </div>

                {{-- Password confirmation --}}
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Create account</button>
            </form>
        </div>
    </div>
@endsection
