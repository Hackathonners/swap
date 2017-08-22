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

            <form role="form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                {{-- E-mail address --}}
                <div class="form-group">
                    <label for="email">E-mail address</label>
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Reset password</button>
            </form>
        </div>
    </div>
@endsection
