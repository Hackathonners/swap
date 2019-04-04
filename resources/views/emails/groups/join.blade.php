@component('mail::message')
# Hello {{ $student->name }},

You were invited to join a group for the course **{{ $course->name }}**.

@component('mail::table')
| Student       | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      |
@endcomponent

If you want to join this group, please click on the link below to confirm.

@component('mail::button', ['url' => $confirmationUrl])
Join group
@endcomponent

Thank you,<br>
\- The team at {{ config('app.name') }}
@endcomponent