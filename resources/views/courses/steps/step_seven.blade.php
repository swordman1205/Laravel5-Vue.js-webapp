<step-seven v-if="step === 7" @update-step="updateStep"
            :course="course"
            :offer="offer"
            :company="{{$company}}"
            inline-template>
    <div class="container-fluid">
        <div class="row d-flex">
            <div class="registrations-steps--box col-lg-7  p-b-20">

                <element-clip-loader :loadingprop="false"
                                     :color="'#f4812e'"
                                     :size="'100px'"
                                     :text="'Salvataggio...'">
                </element-clip-loader>

                <div v-show="!isLoading()" class="float-lg-right registrations-steps--box__form-container">
                    <div class="form-group">
                        <legend>Qual è la tua quota associativa/iscrizione</legend>
                        <div class="form-inline">
                            <input type="number"
                                   class="registrations-steps--box__input-field-short form-control form-input"
                                   :class="{'is-danger': errors.has('price')}"
                                   :disabled="offer.isIncluded"
                                   v-model="offer.quote" name="price"
                                   style="z-index: 1"
                                   v-validate="'required'"/>
                            <div class="input-group-append">
                                <span class="input-group-text mobile-hidden" id="basic-addon1">€</span>
                            </div>
                            <b-form-checkbox class="form-check-label form-input"
                                             v-model="offer.isIncluded">
                                Inclusa nel prezzo
                            </b-form-checkbox>
                        </div>
                        <p class="text-danger" v-if="errors.has('price')">
                            @{{errors.first('price') }}</p>
                    </div>
                    <div class="form-group">
                        <legend>Offri pacchetti di lezioni?</legend>
                        <div class="form-space-between">
                            <b-button
                                    class="btn form-button-rounded float-right pr-5 pl-5 registrations-steps--box__sell-mode-button"
                                    :class="{'form-button-selected': offer.hasLessons}"
                                    @click="offer.hasLessons = true">Si
                            </b-button>
                            <b-button
                                    class="btn form-button-rounded form-button-right-margin float-right pr-5 pl-5 registrations-steps--box__sell-mode-button"
                                    :class="{'form-button-selected': !offer.hasLessons}"
                                    @click="offer.hasLessons = false">No
                            </b-button>
                        </div>
                    </div>
                    <div class="form-group">
                        <legend>Offri abbonamenti?</legend>
                        <div class="form-space-between">
                            <b-button
                                    class="btn form-button-rounded float-right pr-5 pl-5 registrations-steps--box__sell-mode-button"
                                    :class="{'form-button-selected': offer.hasSubscription}"
                                    @click="offer.hasSubscription = true">Si
                            </b-button>
                            <b-button
                                    class="btn form-button-rounded form-button-right-margin float-right pr-5 pl-5 registrations-steps--box__sell-mode-button"
                                    :class="{'form-button-selected': !offer.hasSubscription}"
                                    @click="offer.hasSubscription = false">No
                            </b-button>
                        </div>
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">

                        @if(isset($course))
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    :disabled="!offer.isIncluded && !offer.quote"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    :disabled="!offer.isIncluded && !offer.quote"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                    </div>

                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">Perché inserire la quota associativa?</h5>
                    <p> Le Associazioni sportive dilettantistiche per essere presenti su OrangoGo ed essere tutelate,
                        devono esporre la quota associativa. </p>
                    <p> Se non sei un’ASD verrà esposta la tua quota di iscrizione. </p>
                    <div class="col-md-12 course_text-warning">
                        <p> <span class="text-danger">Attenzione</span>:
                            A tutela del regime fiscale delle ASD, OrangoGo esporrà la quota associativa
                            sulla pagina del corso.</p>
                        <p> Se non iserisci la tua quota associativa, il tuo corso non verrà pubblicato su OrangoGo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</step-seven>