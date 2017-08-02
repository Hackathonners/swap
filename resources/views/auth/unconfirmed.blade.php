@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="alert alert-warning" role="alert">
            <h4>Account verification is required.</h4>
            <p>This area is for verified students only. Please check your student email for the confirmation link, including the SPAM folder.</p>
            <br>
            <form action="{{ route('register.resend_confirmation') }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning">Resend confirmation e-mail</button>
            </form>
        </div>
    </div>
</div>
@endsection
