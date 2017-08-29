@component('mail::message')
# Hello {{ $fromStudent->user->name }},

**Unfortunately, {{ $toStudent->user->name }} declined your requested exchange.**<br>
You are still enrolled in shift **{{ $toShift->tag }}** of the **{{ $course->name }}** course.

@component('mail::button', ['url' => route('dashboard')])
Request a new exchange
@endcomponent

Thank you,<br>
\- The team at {{ config('app.name') }}
@endcomponent
