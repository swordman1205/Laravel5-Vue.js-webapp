@component('mail::layout')
<div class="horizontal-padding-20">
<p>Gentilissima {{ $company->name }},<br>
Hai ricevuto una richiesta di informazioni sul portale <a href="{{ $_SERVER['APP_URL'] }}">OrangoGo.it</a>
</p>
<p>
<strong>Mittente: </strong> {{$email}}<br>
<strong>Messaggio:</strong><br>{{ $message }}
</p>
</div>
<p class="small-text">Attenzione: anche altre persone potrebbero aver bisogno di questa informazione. Riportala sul tuo profilo di OrangoGo cos&igrave; che nessuno abbia scuse per abbandonare la tua pagina!</p>
<p class="center pt-10"><a href="{{ $_SERVER['APP_URL'] . "/societa_sportive/" . $company->slug }}/dashboard" class="button">Aggiorna informazioni</a></p>
<p class="pt-20 center small-text">Se hai bisogno di maggiori informazioi rispondi a questa email o scrivi a<br><a href="mailto:info@orangogo.it">info@orangogo.it</a></p>
<p class="center pt-20 tiny-text">In qualsiasi momento puoi consultare la nostra privacy policy cliccando qui: <a href="{{ $_SERVER['APP_URL'] . "/privacy"}}">privacy policy</a>.</p>
@endcomponent