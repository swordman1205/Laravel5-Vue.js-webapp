<vue-footer inline-template v-cloak>
    <div class="footer">
        <b-container>
            <div class="footer__contact">
                <p>Sport Grand Tour Srl</p>
                <p>Sede Legale: Via Maria Vittoria 38, 10123 Torino (TO)</p>
                <p>Sede Operativa: Corso Valdocco 2, 10122 Torino (TO)</p>
                <p>Iscritta al registro delle imprese di Torino REA: TO-123167</p>
                <p>CF e P.lva: 11661350014 Capitale Sociale: 11,000€ I.V.</p>
            </div>
            <div class="footer__information">
                <h5><a href="{{route('about')}}">CHI SIAMO</a></h5>
                <h5><a href="{{route('contact_us')}}">CONTATTACI</a></h5>
                <h5><a href="{{route('terms')}}">TERMINI DI UTILIZZO</a></h5>
                <h5><a href="{{route('privacy')}}">PRIVACY E COOKIES</a></h5>
            </div>
        </b-container>
    </div>
</vue-footer>