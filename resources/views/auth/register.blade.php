@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header"><strong>Register</strong></div>
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
                                <label for="email">E-mail address</label>
                                <div class="input-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                    <input id="email" type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                    <span class="input-group-addon">@alunos.uminho.pt</span>
                                </div>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
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
            </div>
        </div>
    </div>
@endsection
