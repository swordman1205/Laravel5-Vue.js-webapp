@component('mail::layout')
<p>Ciao {{ $user->first_name}} {{$user->last_name}},<br>
Ti confermiamo di aver ricevuto al tua richiesta di prenotazione e di averla inviata a {{ $reservation->company->name }}.
</p>
<p class="">Riepilogo prenotazione:</p>
<p>
<strong>Corso: </strong> {{$course->name}}<br>
<strong>Dove: </strong> {{$reservation->company->name}}<br>
<strong>Indirizzo:</strong> {{$course->address}}<br>
<strong>Quando: </strong>{{ \Carbon\Carbon::parse($reservation->datetime)->format("d/m/Y H:i") }}<br>
</p>
<p class="">
<strong>Prenotazione a nome di: </strong> {{$sportsman}}
</p>
<p class="">
Per la prima prova {{$course->company->name}} ti consiglia di portare:<br>
{{ $course->lesson_equipments }}
</p>
<p class="center pt-10"><a href="{{ $_SERVER['APP_URL'] . "/corsi/" . $course->slug }}" class="button">Visualizza il corso</a></p>
<div class="horizontal-padding-20 pt-10">
<div class="border-orangogo pt-10">
<p class="center">COME FUNZIONA:</p>
<ol>
<li><strong>Attendi la conferma</strong> da parte della societ&agrave; sportiva;</li>
<li>Porta con te questa email (stampata o su smartphone);</li>
<li>Recati alla lezione.</li>
</ol>
<p class="horizontal-padding-20">In caso di cambi di programma {{ $reservation->company->name }} ti avviser&agrave; su questo indirizzo email.<br>
Se hai bisogno di contattare la societ&agrave; direttamente puoi farlo da qui:
</p>
<p class="center"><a href="mailto:{{ $reservation->company->email }}">scrivi alla societ&agrave;</a>
</div>
</div>
<p class="pt-20 center small-text">Se hai bisogno di maggiori informazioni rispondi a questa email o scrivi a<br><a href="mailto:info@orangogo.it">info@orangogo.it</a></p>
<p class="center pt-20 tiny-text">In qualsiasi momento puoi consultare la nostra privacy policy cliccando qui: <a href="{{ $_SERVER['APP_URL'] . "/privacy"}}">privacy policy</a>.</p>
@endcomponent