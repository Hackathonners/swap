@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-header"><strong>Login</strong></div>
                <div class="card-block">
                    <form role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email">E-mail address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <div class="form-control-feedback"><small>{{ $errors->first('email') }}</small></div>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <div class="form-control-feedback"><small>{{ $errors->first('password') }}</small></div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input  class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
