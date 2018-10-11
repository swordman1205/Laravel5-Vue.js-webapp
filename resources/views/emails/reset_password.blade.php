@component('mail::layout')
<h2>Reset password</h2>
<p>Ciao {{ $user->first_name }},<br>
Qualcuno ha richiesto il reset della password per il tuo account OrangoGo.<br>
</p>
<p>Se non lo hai richiesto tu, puoi semplicemente ignorare questa email.</p>
<p>Altrimenti clicca sul link di seguito per completare il reset della password</p>
<p class="center pt-10"><a href="{{ $_SERVER['APP_URL'] }}/password/reset/{{$token}}" class="button">Reset Password</a></p>
@endcomponent
