@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" role="alert">
        <div class="card-block">
            <h4 class="card-title text-warning">Account verification is required</h4>
            <p class="card-text text-muted">This area is for verified students only. Please check your student email for the confirmation link, including the SPAM folder.</p>
            <form action="{{ route('register.resend_confirmation') }}" method="post">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-warning">Resend confirmation e-mail</button>
            </form>
        </div>
    </div>
</div>
@endsection
