@component('mail::layout')
    <h2>Un nuovo sportivo si &egrave; registrato</h2>
<p class="text-monospace">
<strong>Email: </strong> {{$user->email}}<br>
<strong>Nome:</strong> {{ $name }}
</p>
@endcomponent
