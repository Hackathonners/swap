@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Log in to your account</div>
        <div class="card-body">
            <form role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                {{-- E-mail address --}}
                <div class="form-group">
                    <label for="email">E-mail address</label>
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" required>
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                </div>

                {{-- Remember me --}}
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Login</button>

                {{-- Forgot password link --}}
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            </form>
        </div>
    </div>
@endsection
