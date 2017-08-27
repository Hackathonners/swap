@extends('layouts.app')

@section('content')
    <div class="card card--section">
        <div class="card-body p-5 text-center" role="alert">
            <h4 class="card-title"><small>Account verification is required.</small></h4>
            <p class="card-text text-muted">
                Your account must be verified in order to allow you to access this area.
                <br>
                Please check your student email for the confirmation link, including the SPAM folder.
            </p>
            <form action="{{ route('register.resend_confirmation') }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning">Resend confirmation e-mail</button>
            </form>
        </div>
    </div>
@endsection
