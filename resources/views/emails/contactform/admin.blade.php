@component('mail::layout')
    <h2>Un nuovo utente ti ha scritto!</h2>
<p class="text-monospace">
<strong>Email: </strong> {{$email}}<br>
<strong>Messaggio:</strong><br>{{ $message }}
</p>
@endcomponent
