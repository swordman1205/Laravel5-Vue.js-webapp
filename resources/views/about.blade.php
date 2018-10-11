@extends('layouts.main')
@section('title', 'About us')
@section('content')
<about-us inline-template v-cloak>
  <b-container fluid class="about-us-container">
    <b-row>
      <b-col cols="12">
        <div class="about-us-container__header">
          <h2 class="about-us-container__header__title">
            Chi Siamo?
          </h2>
          <p class="about-us-container__header__description">
            <span class="about-us-container__text--orango">OrangoGo</span> nasce con l’obiettivo di permettere a tutti di trovare la propria passione sportiva!
          </p>
          <div class="about-us-container__header__main">
            <p>Crediamo nella capacità di ogni individuo, fin da bambino, di prendere coscienza delle proprie competenze, capacità, passione e talento.</p>
            <p>Intendiamo rivoluzionare l’approccio all’attività fisica con strumenti pratici che aiutino le persone a scegliere lo sport più adatto per migliorarsi e per esserne soddisfatti.</p>
            <p>Desideriamo offrire a tutti la possibilità di trovare facilmente gli sport sul territorio e promuoviamo una maggiore libertà nel periodo di scoperta, nell’ottica di una scelta consapevole e funzionale anche attraverso un percorso multisport.</p>
          </div>
          <div class="about-us-container__header__end">
            <p>Vogliamo essere, in Europa, il punto di riferimento di una cultura in cui lo Sport è lo strumento per vivere una vita più felice, grazie alla scoperta delle proprie passioni ed alla consapevolezza del proprio talento.</p>
          </div>
        </div>
      </b-col>
    </b-row>
    <hr>
    <b-row>
      <b-col cols="12" lg="6" class="about-us-container__section about-us-container__section--left">
        <h3 class="about-us-container__section__title">Cosa Facciamo?</h3>
        <p class="about-us-container__section__content">
          <span class="about-us-container__text--orango">OrangoGo</span> ti permette di trovare tutti gli sport vicini a te e di scegliere quello che ti interessa. 
          <br><br>
          La ricerca può essere filtrata per <strong>giorni, orario, età e disabilità</strong> e tutti possono prenotare la prima prova gratuita presso la Società Sportiva con un click. 
          <br><br>
          Alcune Società Sportive, inoltre, danno a tutti gli sportivi che prenotano la prima prova gratuita tramite OrangoGo.it, il <strong>10% di sconto sull’abbonamento annuale.</strong>
          <br><br>
          Orangogo ha introdotto il riconoscimento “Family Friendly”, Società Sportive che si impegnano attivamente nella promozione dello sport come strumento educativo, con un approccio che favorisca lo sviluppo del massimo potenziale umano tra i più giovani. 
          <br><br>
          Le Società Sportive “Family Friendly” offrono la possibilità <strong>ai bambini dai 5 ai 14 anni</strong> di praticare lo sport per due mesi ad un costo fisso. Al termine dei due mesi è possibile confermare il proprio abbonamento annuale o cambiare sport, alla ricerca della propria passione sportiva. 
          <br><br>
          Le Società Sportive che aderiscono a questo innovativo modello di orientamento sono identificate con la faccina dell’Orango.
        </p>
      </b-col>
      <b-col cols="12" lg="6" class="about-us-container__section about-us-container__section--right">
        <h3 class="about-us-container__section__title">Perchè lo Facciamo?</h3>
        <p class="about-us-container__section__content">
          <span class="about-us-container__text--orango">Everybody Sports</span>
          <br><br>
          Tutti devono aver la possibilità di scoprire la felicità del fare ciò che appassiona. 
          <br><br>
          Chiunque, senza discriminazioni di età, razza, genere e status, deve avere l’opportunità di incontrare e vivere una passione sportiva. 
          <br><br>
          La scoperta del proprio talento, non solo sportivo, permette ad ogni individuo di far emergere e manifestare il proprio valore umano. 
          <br><br>
          Abbiamo deciso di rivoluzionare un mondo ormai passato, spostando i criteri di scelta di uno sport verso l’individuo. Prima si sceglieva lo sport in base alla comodità o al vicino di banco, ora tutto ruota attorno alla persona: propensione personale, fisico e carattere. 
          <br><br>
          Vogliamo permettere a tutti di conoscere tutte le opportunità sportive vicino a casa e accedere agli sport con una modalità che stimoli curiosità e favorisca l’individuazione dei propri gusti, passioni e talenti.
        </p>
        <div class="about-us-container__section__objectives">
          <h4 class="about-us-container__section__objectives__title">I nostri Obiettivi di Impatto Sociale</h4>
          <p class="about-us-container__section__objectives__aim">- Aumentare il numero di persone sportivamente attive</p>
          <p class="about-us-container__section__objectives__aim">- Diminuire l’abbandono in età adolescenziale (drop-out sportivo)</p>
          <p class="about-us-container__section__objectives__aim">- Aumentare le performance degli atleti di alto livello</p>
          <p class="about-us-container__section__objectives__aim">- Aumentare il benessere psicofisico della collettività</p>
        </div>
      </b-col>
    </b-row>
    <hr>
    <b-row>
      <b-col cols="12" class="about-us-container__team">
        <h2 class="about-us-container__team__title">Il Team</h2>
        <b-row>
          <b-col cols="12" md="6" lg="4" v-for="(member, index) in members" :key="index">
            <div class="about-us-container__team__member">
              <div class="about-us-container__team__member__avatar">
                <img :src="member.avatar">
              </div>
              <h5 class="about-us-container__team__member__name">@{{member.name}}</h5>
              <h5 class="about-us-container__team__member__role">@{{member.role}}</h5>
            </div>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </b-container>
</about-us>
@endsection
