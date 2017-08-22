@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-header">Reset password</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form role="form" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                {{-- E-mail address --}}
                <div class="form-group">
                    <label for="email">E-mail address</label>
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
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
                <button type="submit" class="btn btn-primary">Reset password</button>
            </form>
        </div>
    </div>
@endsection
