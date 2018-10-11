<step-nine v-if="step === 9"
            @update-step="updateStep"
            :course="course"
            :company="{{$company}}"
            inline-template>
    <div class="container-fluid">
        <div class="row d-flex">
            <div class="registrations-steps--box col-lg-7 p-b-20">

                <element-clip-loader :loadingprop="false"
                                     :color="'#f4812e'"
                                     :size="'100px'"
                                     :text="'Salvataggio...'">
                </element-clip-loader>

                <div v-show="!isLoading()" class="float-lg-right registrations-steps--box__form-container">
                    <div class="form-group">
                        <legend>Abbonamento al corso</legend>
                        <div v-for="(subscription, index) in course.subscriptions"
                             :key="index">
                            <span>DURATA E COSTO RELATIVO</span>
                            <div class="form-inline">
                                <b-form-select
                                        v-model="subscription.subscriptionId"
                                        value-field="id"
                                        text-field="name"
                                        :options="mappedSubscriptions"
                                        class="form-control"
                                        :class="{'is-danger': subscription.validationError.subscriptionId}">
                                </b-form-select>

                                <span style="margin-left: 1rem; margin-right: 1rem">a</span>
                                <input placeholder="es. 100 euro"
                                       type="number"
                                       class="form-control form-input registrations-steps--box__input-field-short"
                                       style="max-width: 150px; z-index: 1;"
                                       :class="{'is-danger': errors.has('price') || subscription.validationError.price }"
                                       v-model="subscription.price"
                                       name="price"
                                       v-validate="'required'"/>
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                          id="basic-addon1">€</span>
                                </div>
                                <span @click="removeSubscription(index)"
                                      style="cursor: pointer; margin-left: 1rem"><img
                                            src="/images/close-icon.svg"
                                            alt=""></span>
                            </div>
                            <br>
                            <span>SCADENZA ISCRIZIONI</span>
                            <datepicker class="form-datepicker"
                                        :class="{'is-danger': subscription.validationError.registrationDeadline}"
                                        v-model="subscription.registrationDeadline">
                            </datepicker>
                            <hr>
                        </div>
                    </div>
                    <div class="form-group">
                        <img class="form-plus-icon"
                             src="/images/plus-button.svg"
                             @click="addSubscription"/>
                        <span style="font-weight: bold; margin-left: 1rem">AGGIUNGI ABBONAMENTO</span>
                    </div>
                    <div class="form-group">
                        <legend>Cosa è incluso nel prezzo</legend>
                        <vue-tags-input
                                v-model="includedServiceTag"
                                :tags="course.includedServices"
                                :autocomplete-items="filteredIncludedServices"
                                :add-only-from-autocomplete="true"
                                @tags-changed="newTags => course.includedServices = newTags">
                        </vue-tags-input>
                    </div>
                    <div class="form-group">
                        <legend>Cosa non è incluso nel prezzo</legend>
                        <vue-tags-input
                                v-model="excludedServiceTag"
                                :tags="course.excludedServices"
                                :autocomplete-items="filteredExcludedServices"
                                :add-only-from-autocomplete="true"
                                @tags-changed="newTags => course.excludedServices = newTags">
                        </vue-tags-input>
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">

                       @if(isset($course))
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Ho finito
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Ho finito <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                    </div>

                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">Hai abbonamenti per tutte le esigenze?</h5>
                    <p>Quali formule sono disponibili per questo corso?</p>

                    <p>Inserisci tutte le tue formule per permettere a
                        famiglie e sportivi di trovare quella più adatta alle loro esigenze.</p>
                </div>
            </div>
        </div>
    </div>
</step-nine>