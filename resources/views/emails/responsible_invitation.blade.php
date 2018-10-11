@component('mail::layout')
<p>Ciao {{$course->responsible_name}},<br>
la societ&agrave; {{ $course->sport->name }} ti invita a gestire:</p>
<p class="text-monospace">
<strong>Corso: </strong>{{ $course->name }}<br>
<strong>Indirizzo:</strong> {{$course->address}}<br>
<strong>Comune: </strong>{{ $course->postal_code }} {{ $course->municipality }} ({{ $course->province }})<br>
<p class="center pt-10"><a href="{{ $_SERVER['APP_URL'] . "/corsi/" . $course->slug }}" class="button">Visualiza il Corso</a></p>
@endcomponent