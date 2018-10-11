@component('mail::layout')
<p class="center">Complimenti {{ $name }},</p>
<p class="center">sei su <strong>OrangoGo!</strong></p>
<p>Accedi alla tua area riservata per gestire il tuo profilo e le tue prenotazioni.</p>
<p class="center pt-10"><a href="{{route('home')}}" class="button">Apri OrangoGo</a></p>
<p class="pt-20 center small-text">Se hai bisogno di maggiori informazioni rispondi a questa email o scrivi a<br><a href="mailto:info@orangogo.it">info@orangogo.it</a></p>
@endcomponent