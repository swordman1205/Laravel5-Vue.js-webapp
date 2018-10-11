@extends('layouts.main', ["nav_active" => 1])
@section('title', 'Orangogo')
@section('content')
    <div class="hero-box">
        @if (Auth::check())
            <sportsclubs :userprop="{{Auth::user()}}" inline-template v-cloak>
                @else
                    <sportsclubs inline-template v-cloak>
                        @endif
                        <div>
                            <page-clip-loader :loading="true"
                                              :color="'#f4812e'"
                                              :size="'100px'"
                                              :text="'Registrazione in corso'">
                            </page-clip-loader>
                            <div class="row">
                                <div class="col-1 scroll-box">
                                    <div class="scroll-box--text">
                                        <span>scroll down</span>
                                        <div class="dot"></div>
                                        <img src="/images/left-arrow.svg" alt="" id="arrow-down">
                                    </div>
                                </div>
                                <div class="jumbotron jumbotron-fluid col-11 hero-box--jum">
                                    <div class="container h-100">
                                        <div class="row align-items-center h-100">
                                            <div class="col-md-6 jumbotron-text">
                                                <h1 class="display-4 ">Perchè scegliere Orangogo?</h1>
                                                <p class="fs--16">Orangogo è il motore di ricerca degli sport. <br> Una piattaforma
                                                    totalmente gratuita, meritocratica e trasparente. Aggiungi la tua società
                                                    sportiva e raggiungi il massimo del tuo pubblico!</p>
                                                <a href="#howworks"><button type="button" class="xs-hidden btn orango-btn">SCOPRI DI
                                                        PIU'</button></a>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="company-form-container">
                                                    <form id="registration-form">
                                                        <div class="form-group">
                                                            <input type="text" v-model="company.name"
                                                                   id="registration-company__name-field"
                                                                   placeholder="Nome della società">
                                                        </div>
                                                        <div class="form-group">
                                                            <vue-google-autocomplete
                                                                    id="registration-company__address-field"
                                                                    class="form-control"
                                                                    placeholder="Indirizzo della Società"
                                                                    @place_changed="getCompanyAddressData"
                                                                    :types="types">
                                                            </vue-google-autocomplete>
                                                        </div>
                                                        @if (!Auth::check())
                                                            <div class="form-group">
                                                                <input type="email" v-model="user.email"
                                                                       id="registration-company__email-field"
                                                                       placeholder="E-mail">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="password" v-model="user.password"
                                                                       id="registration-company__password-field"
                                                                       placeholder="Password (minimo 6 caratteri)">
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <b-form-checkbox v-model="accept_field" id="registration-company__accept-field">
                                                                <span style="color: #fff;">Creando un account accetti automaticamente i <a href="/terms">Termini e le Condizioni</a> e <a
                                                                        href="/privacy">l’Informativa sulla Privacy</a></span>
                                                            </b-form-checkbox>

                                                        </div>
                                                        <div class="form-group">
                                                            <button id="register-button" type="submit"
                                                                    @click="registerCompany()"
                                                                    class="dark-blue-gradient-button">Registrati
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Confirmation modal registration -->
                                            <div class="modal" id="confirmRegistration">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="text-center m-t-20">
                                                            <h3 class="text-center"><b>Benvenuto a bordo!</b></h3>
                                                            <p>Complimenti, la pagina della tua società sportiva è stata
                                                                creata con
                                                                successo.</p>
                                                        </div>
                                                        <hr>
                                                        <!-- Modal body -->
                                                        <div class="modal-body text-center">
                                                            <p>Che aspetti? Crea il tuo primo corso su Orangogo!</p>
                                                            <div>
                                                                <a href="{{route('courses.create')}}">
                                                                    <button class="dark-blue-gradient-button">Crea
                                                                        corso
                                                                    </button>
                                                                </a>
                                                            </div>
                                                            <div class="m-t-20">
                                                                <a class="font-color-orange text-uppercase"
                                                                   :href="getCompanyDashboardUrl()">Vai alla tua
                                                                    dashboard</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Error User Exist modal -->
                                            <div class="modal" id="confirmRegistration">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <h2>Questa email è già stata utilizzata su OrangoGo</h2>
                                                            <div></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <howorangogowork></howorangogowork>
                                <!-- Info Box -->
                                <div class="container-fluid w--90">
                                    <div class="row">
                                        <div class="col-12 info-box text-center mt-5 pt-5">
                                            <h1 class="mb-4">La presenza sul Motore di ricerca degli sport è, e resterà,
                                                gratuita.</h1>
                                            <p class="w-md-50 mx-auto">OrangoGo vuole essere, in Europa, il punto di
                                                riferimento di una cultura in cui lo Sport è lo strumento per vivere una
                                                vita più felice, grazie alla scoperta delle proprie passioni ed alla
                                                consapevolezza del proprio talento.</p>
                                            <hr class="w-100">
                                        </div>

                                        <div class="col-12 info-box text-center mt-5 pt-5">
                                            <div class="col-6 info-box--img float-left h-75">
                                                <img class="my-3 w-75" src="/images/landing/pagina_dedicata.svg"
                                                     alt="info-img">
                                            </div>
                                            <div class="col-6 info-box--text float-right text-left h-75">
                                                <h4 class=" w-md-50 font-weight-bold mb-3 mt-5">Crea la tua pagina
                                                    dedicata</h4>
                                                <p class="w-md-50 fs-14">Inserisci le informazioni sul tuo sport, sugli orari dei tuoi corsi e sull’età dei tuoi sportivi.

                                                    In pochi semplici step avrai una bella pagina sul Motore di ricerca degli sport</p>
                                            </div>
                                            <hr class="w-100">
                                        </div>

                                        <div class="col-12 info-box text-center mt-5 pt-5">
                                            <div class="col-6 info-box--text float-left h-75 align-content-start flex-wrap">
                                                <h4 class=" w-md-50 font-weight-bold mb-3 mt-5 text-left d-inline-block">Gestisci le richieste di famiglie e sportivi.</h4>
                                                <p class=" w-md-50 d-inline-block text-left">Inserendo le informazioni sui tuoi sport, potrai getisire comodamente il calendario delle tue lezioni nella tua area privata.</p>
                                            </div>
                                            <div class="col-6 info-box--img float-right h-75">
                                                <img class="my-3 w-75" src="/images/landing/manage_courses.svg"
                                                     alt="info-img">
                                            </div>
                                            <hr class="w-100">
                                        </div>

                                        <!--<div class="col-12 info-box text-center mt-5 pt-5">
                                        <div class="col-6 info-box--img float-left h-75">
                                        <img class="my-3 w-75" src="/images/landing/manage_users.svg"
                                        alt="info-img">
                                        </div>
                                        <div class="col-6 info-box--text float-right text-left h-75">
                                        <h4 class=" w-md-50 font-weight-bold mb-3 mt-5">Monitora i dati dei tuoi
                                        utenti</h4>
                                        <p class=" w-md-50 ">Lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit.
                                        Architecto
                                        dignissimos, dolor dolorem doloremque doloribus dolorum eius
                                        esse.</p>
                                        </div>
                                        <hr class="w-100">
                                        </div>-->

                                        <div class="col-12 info-box mt-5 pt-5" style="margin-bottom: 10rem;">
                                            <h4 class="mb-5 font-weight-bold">Siamo sempre di più</h4>
                                            <div class="jumbotron jumbotron-more h--85 d-flex ">
                                                <div class="jumbotron-more--content pb-3 pt-3 my-auto mx-auto w--80 d-flex row">
                                                    <h3 class="font-weight-bold col-md-4 float-left my-auto mx-auto">
                                                        +7000</h3>
                                                    <p class="col-md-4 float-left my-auto mx-auto font-weight-bold">
                                                        Associazioni sportive su OrangoGo</p>
                                                    <button @click="$store.dispatch('onShowRegister')"
                                                            class="btn float-right col-md-3 offset-md-1 my-auto">
                                                        Registrati
                                                    </button>
                                                </div>
                                            </div>
                                            <hr class="w-100">
                                        </div>
                                    </div>
                                </div>

                                <!-- END Infobox -->
                                {{--<reviews></reviews>--}}
                                <div class="container-fluid d-flex container-form" id="contact_us">
                                    <div class="my-auto mx-auto">
                                        <div class="contact-form row">
                                            <div class="contact-form--text col-md-6 float-left">
                                                <h5 class="mt-3 mb-3">HAI ULTERIORI DOMANDE?</h5>
                                                <h1 class="font-weight-bold w-75">Contattaci, siamo sempre a disposizione</h1>
                                            </div>
                                            <div class="col-md-6">
                                                <form class="contact-form--box" id="contactform">
                                                    @csrf
                                                    <input class="w-100 form-control mb-3" type="email" name="email" placeholder="La tua email">
                                                    <textarea class="d-block w-100 form-control hv-25 mb-3" name="message" type="text"
                                                              placeholder="Il tuo messaggio"></textarea>
                                                    <button class="float-right btn" type="submit">Invia</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </sportsclubs>
    </div>
@endsection
@section('script')
    <script>
        window.baseUrl = '{{env('APP_URL')}}'
        window.googleMapsAPI = '{{env('GOOGLE_MAPS_API')}}'
    </script>
@endsection
@section('scripts')
    <script>
        $("#contactform").submit(function(e) {
            e.preventDefault();
            $.post("{{route("contactform")}}", $("#contactform").serialize(), function(risp) {
                window.toastr.success("sarai ricontattato da un nostro esperto nel piu breve tempo possibile", "Messaggio inviato");
            }).fail(function(xhr, status, error) {
                window.toastr.warning("controlla i campi prima di inviare il messaggio", "Messaggio non inviato");
            });
            return false;
        });
    </script>
@endsection