@component('mail::layout')
<p>Gentilissima {{ $reservation->company->name }},<br>
Hai ricevuto una prenotazione sul portale <a href="{{ $_SERVER['APP_URL'] }}">OrangoGo.it</a> sul corso:
</p>
<p class="">
<strong>Corso: </strong> {{$course->name}}<br>
<strong>Indirizzo:</strong> {{$course->address}}<br>
</p>
<div class="horizontal-padding-20">
<div class="border-orangogo p-10">
<p class="center">DATI SPORTIVO:</p>
<p class="">
<strong>Chi: </strong> {{$sportsman}}<br>
<strong>Data di nascita: </strong>{{ $birthday }}<br>
<strong>Data della prova: </strong>{{ \Carbon\Carbon::parse($reservation->datetime)->format("d/m/Y H:i") }}<br>
</p>
<p class="center">Controlla i dati dello sportivo!</p>
<p class="center pt-10"><a href="{{ $_SERVER['APP_URL'] . "/societa_sportive/" . $reservation->company->slug }}/dashboard#prenotazioni" class="button">Vai alle prenotazioni</a></p>
</div>
<div class="pt-10 italic supertiny-text">Privacy Policy: ricorda che puoi utilizzare i dati che ti abbiamo fornito solo ed esclusivamente per la gesitone della prenotazione. Non hai il diritto di utilizzarli per altri scopi. Ti consigliamo di raccogliere il consenso al trattamento dei dati al primo accesso dello Sportivo presso la tua struttura.</div>
</div>
<p class="pt-20 center small-text">Se hai bisogno di maggiori informazioi rispondi a questa email o scrivi a<br><a href="mailto:info@orangogo.it">info@orangogo.it</a></p>
<p class="center pt-20 tiny-text">In qualsiasi momento puoi consultare la nostra privacy policy cliccando qui: <a href="{{ $_SERVER['APP_URL'] . "/privacy"}}">privacy policy</a>.</p>
@endcomponent
