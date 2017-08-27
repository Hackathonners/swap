@component('mail::message')
# Hello {{ $name }},

You are about one click from starting to use the {{ config('app.name') }} platform.
Please click on the link below to confirm your student e-mail.

@component('mail::button', ['url' => $confirmationUrl])
Confirm account
@endcomponent

Thank you,<br>
\- The team at {{ config('app.name') }}
@endcomponent
