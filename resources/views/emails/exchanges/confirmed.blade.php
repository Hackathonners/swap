@component('mail::message')
# Good news, {{ $fromStudent->user->name }}!

**{{ $toStudent->user->name }} confirmed your requested exchange.**<br>
You are now enrolled in shift **{{ $toShift->tag }}** of the **{{ $course->name }}** course!

@component('mail::button', ['url' => route('dashboard')])
See your enrollments
@endcomponent

Thank you,<br>
\- The team at {{ config('app.name') }}
@endcomponent
