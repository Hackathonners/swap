@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header"><strong>Reset password</strong></div>
                <div class="card-block">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email">E-mail Address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <div class="form-control-feedback"><small>{{ $errors->first('email') }}</small></div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Reset password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
