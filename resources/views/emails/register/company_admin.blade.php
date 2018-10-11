@component('mail::layout')
<h2>Una nuova società si &egrave; registrata</h2>
<p class="text-monospace">
<strong>Email: </strong> {{$email}}<br>
<strong>Nome:</strong> {{ $name }}
</p>
@endcomponent