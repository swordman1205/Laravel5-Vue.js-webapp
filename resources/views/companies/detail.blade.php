@extends('layouts.main')
@section('title', 'Company')
@section('content')
    <company-detail :company="{{$company}}" :courses="{{$courses}}" :sports-prop="{{$sports}}" :images="{{$images}}"
                    inline-template v-cloak>
        <div class="company-detail">
            <b-container>
                <div class="company-detail__header">
                    <img class="company-detail__header__logo" :src="company.logo_path" alt="Company Logo here">
                    <div>
                        <h1 class="company-detail__header__name">{{$company->public_name}}</h1>
                        <div class="d-none align-items-center d-lg-flex">
                            <b-button variant="outline-secondary" class="company-detail__header__btn--contact"
                                      href="#contact-us-form">CONTATTACI
                            </b-button>
                            <div class="d-flex align-items-center w-100" v-if="company.federation_id">
                                <div class="company-detail__header__federation">@{{company.federation.name}}</div>
                            </div>
                        </div>
                        <div class="d-none company-detail__header__practiced d-lg-block" v-if="sports.length > 0">
                            <h5>Sport praticati</h5>
                            <div>
                                <template v-for="sport in sports">
                                    <span class="company-detail__header__practiced__sport">@{{sport}}</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-column-reverse d-lg-none">
                    <b-button variant="outline-secondary" class="company-detail__header__btn--contact"
                              href="#contact-us-form">CONTATTACI
                    </b-button>
                    <div class="d-flex align-items-center w-100" v-if="company.federation_id">
                        <div class="company-detail__header__federation">@{{company.federation.name}}</div>
                    </div>
                </div>
                <div class="company-detail__header__practiced d-lg-none" v-if="sports.length > 0">
                    <h5>Sport praticati</h5>
                    <div>
                        <template v-for="sport in sports">
                            <a href="javascript:void(0)" class="company-detail__header__practiced__sport">@{{sport}}</a>
                        </template>
                    </div>
                </div>
                <div class="company-detail__courses">
                    <h2 class="company-detail__courses__title">Corsi disponibili</h2>
                    <home-carousel :count-prop="4" :items="courses"></home-carousel>
                </div>
                <div class="company-detail__additional">
                    <b-row class="flex-column-reverse flex-lg-row">
                        <b-col cols="12" lg="3" class="p-0 p-lg-3">
                            <div class="company-detail__additional__map">
                                <div class="company-detail__additional__map__header">
                                    <img src="/images/map-marker.svg">
                                    <div>
                                        <h6>Indirizzo sede</h6>
                                        <p>@{{company.address}}</p>
                                    </div>
                                </div>
                                <google-map ref="mapRef"
                                            :center="{ lat: company.latitude * 1, lng: company.longitude * 1 }"
                                            :zoom="10"
                                            :style="{width: '100%', height: '300px'}">
                                    <google-map-marker
                                            :position="{ lat: company.latitude * 1, lng: company.longitude * 1 }"
                                            icon="/images/map-marker.svg">
                                    </google-map-marker>
                                </google-map>
                            </div>
                        </b-col>
                        <b-col cols="12" lg="9" class="p-0 p-lg-3 d-flex flex-column-reverse d-lg-block">
                            <photo-gallery :photos="images" v-if="images.length > 0"></photo-gallery>
                            <div class="company-detail__additional__text-collapsible" v-if="company.description">
                                <p>@{{calcDescription}}</p>
                                <a href="javascript:void(0)" @click="loadMoreDesc" v-if="isLoadMore">@{{ !descExpanded ?
                                    'LEGGI DI PIù' : 'mostra meno' }}</a>
                            </div>
                            <div v-else>
                                <p><i>Nessuna descrizione inserita per la società {{$company->public_name}}</i></p>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <!-- <div class="company-detail__reviews">
                  <h2 class="company-detail__reviews__title">54 Recensioni</h2>
                  <p class="company-detail__reviews__description">Le recensioni degli utenti che hanno partecipato ai nostri corsi!</p>
                  <b-row>
                    <b-col cols="12" sm="4" v-for="review in reviews">
                      <div class="company-detail__reviews__card">
                        <div class="company-detail__reviews__card__avatar">
                          <img :src="review.user.avatar">
                        </div>
                        <div>
                          <div class="company-detail__reviews__card__rating">
                            <i class="fa-star" :class="{fas: i <= review.rating, far: i > review.rating}" v-for="i in [1, 2, 3, 4, 5]" :key="i"></i>
                          </div>
                          <h5 class="company-detail__reviews__card__title">@{{review.title}}</h5>
                          <p class="company-detail__reviews__card__description">@{{review.description}}</p>
                          <hr>
                          <span class="company-detail__reviews__card__user-name">@{{review.user.name}}</span>
                        </div>
                      </div>
                    </b-col>
                  </b-row>
                </div> -->
                <div class="company-detail__footer">
                    <b-row>
                        <b-col cols="12" lg="10" offset-lg="1" class="p-0 p-lg-3">
                            <b-row class="row flex-column-reverse flex-lg-row">
                                <b-col cols="12" lg="6" class="company-detail__footer__contact p-0 p-lg-3"
                                       id="contact-us-form">
                                    <h2 class="company-detail__contact__title">Contattaci</h2>

                                    <div class="col-md-12">
                                        <form class="contact-form--box" id="contactform">
                                            @csrf
                                            <input type="hidden" name="company" value="{{$company->id}}">
                                            <input class="w-100 form-control mb-3" type="email" name="email"
                                                   placeholder="La tua email">
                                            <textarea class="d-block w-100 form-control hv-25 mb-3" name="message"
                                                      type="text"
                                                      placeholder="Il tuo messaggio"></textarea>
                                            <button class="btn white-solid-button btn-outline-secondary" type="submit">
                                                Invia
                                            </button>
                                        </form>
                                    </div>
                                </b-col>
                                <b-col cols="12" lg="6" class="p-0 p-lg-3">
                                    <div class="company-detail__footer__company-info">
                                        <h6><strong>{{$company->public_name}}</strong></h6>
                                        <p class="mb-2">{{$company->legal_form?$company->legal_form->name : ''}}</p>
                                        <h6><strong>P.IVA / Codice fiscale:</strong> @{{company.vat_number}}</h6>
                                        <p class="mb-0"><strong>Indirizzo sede:</strong> @{{company.address}}</p>
                                    </div>
                                    <!--<div class="company-detail__footer__hours">
                                      <h5>Orari di segreteria</h5>
                                      <div>
                                        <div class="company-detail__footer__hours__day" :class="{'company-detail__footer__hours__day--closed': !hour.time}" v-for="hour in hours">
                                          <div>
                                            <h6>@{{hour.day}}</h6>
                                            <p>@{{hour.time || 'CHIUSO'}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>-->
                                </b-col>
                            </b-row>
                        </b-col>
                    </b-row>
                </div>
            </b-container>
        </div>
    </company-detail>
@endsection
@section('scripts')
    <script>
        $("#contactform").submit(function (e) {
            e.preventDefault();
            $.post("{{route("contactform")}}", $("#contactform").serialize(), function (risp) {
                window.toastr.success("sarai ricontattato dal team dell'associazione nel piu breve tempo possibile", "Messaggio inviato");
            }).fail(function (xhr, status, error) {
                window.toastr.warning("controlla i campi prima di inviare il messaggio", "Messaggio non inviato");
            });
            return false;
        });
    </script>
@endsection