@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-warning p-4" role="alert">
            <h4 class="alert-heading">Account verification is required</h4>
            <p>This area is for verified students only. Please check your student email for the confirmation link, including the SPAM folder.</p>
            <br>
            <form action="{{ route('register.resend_confirmation') }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning">Resend confirmation e-mail</button>
            </form>
        </div>
    </div>
@endsection
