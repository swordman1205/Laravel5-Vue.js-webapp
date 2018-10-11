<step-eight v-if="step === 8"
            @update-step="updateStep"
            :course="course"
            :offer="offer"
            :company="{{$company}}"
            inline-template>
    <div class="container-fluid">
        <div class="row d-flex ">
            <div class="registrations-steps--box col-lg-7 p-b-20">

                <element-clip-loader :loadingprop="false"
                                     :color="'#f4812e'"
                                     :size="'100px'"
                                     :text="'Salvataggio...'">
                </element-clip-loader>

                <div v-show="!isLoading()" class="float-lg-right registrations-steps--box__form-container">
                    <div class="form-group">
                        <legend>Pacchetti di lezioni</legend>
                        <div v-for="(lessonPackage, index) in course.lessonPackages"
                             :key="index">
                            <span>N°LEZIONI E COSTO RELATIVO</span>
                            <div class="form-inline">
                                <input placeholder="es. 10 lezioni"
                                       type="number"
                                       style="max-width: 10rem"
                                       class="form-control form-input registrations-steps--box__input-field-short"
                                       :class="{'is-danger': errors.has('number of lessons') || lessonPackage.validationError.price }"
                                       v-model="lessonPackage.nLessons"
                                       name="number of lessons"
                                       v-validate="'required'"/>
                                <span style="margin-left: 1rem; margin-right: 1rem">a</span>
                                <input placeholder="es. 100 euro"
                                       style="z-index: 1;max-width: 10rem;"
                                       type="number"
                                       class="form-control form-input registrations-steps--box__input-field-short"
                                       :class="{'is-danger': errors.has('price') || lessonPackage.validationError.price }"
                                       v-model="lessonPackage.price"
                                       name="price"
                                       v-validate="'required'"/>
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                          id="basic-addon1">€</span>
                                </div>
                                <span @click="removeLessonPackage(index)"
                                      style="cursor: pointer; margin-left: 1rem"><img
                                            src="/images/close-icon.svg"
                                            alt=""></span>
                            </div>
                            <br>
                            <span>PERIODO DISPONIBILITÁ LEZIONI</span>
                            <div class="form-inline">
                                <datepicker class="form-datepicker"
                                            :class="{'is-danger': lessonPackage.validationError.registrationDeadline}"
                                            v-model="lessonPackage.startDate">
                                </datepicker>
                                <span style="margin-left: 1rem; margin-right: 1rem">al</span>
                                <datepicker class="form-datepicker"
                                            :class="{'is-danger': lessonPackage.validationError.registrationDeadline}"
                                            v-model="lessonPackage.endDate">
                                </datepicker>
                            </div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <img class="form-plus-icon"
                                 src="/images/plus-button.svg"
                                 @click="addLessonPackage"/>
                            <span style="font-weight: bold; margin-left: 1rem">AGGIUNGI PACCHETTO</span>
                        </div>
                        <div class="form-group">
                            <legend>Cosa è incluso nel prezzo</legend>
                            <vue-tags-input
                                    v-model="includedServiceTag"
                                    :tags="course.includedLessonServices"
                                    :autocomplete-items="filteredIncludedServices"
                                    :add-only-from-autocomplete="true"
                                    @tags-changed="newTags => course.includedLessonServices = newTags">
                            </vue-tags-input>
                        </div>
                        <div class="form-group">
                            <legend>Cosa non è incluso nel prezzo</legend>
                            <vue-tags-input
                                    v-model="excludedServiceTag"
                                    :tags="course.excludedLessonServices"
                                    :autocomplete-items="filteredExcludedServices"
                                    :add-only-from-autocomplete="true"
                                    @tags-changed="newTags => course.excludedLessonServices = newTags">
                            </vue-tags-input>
                        </div>
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">

                        @if(isset($course))
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                    </div>

                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">Proponi pacchetti per tutte le esigenze?</h5>
                    <p>Quali formule sono disponibili per questo corso?</p>
                    <p>Inserisci tutte le tue formule per permettere a famiglie e sportivi di
                        trovare quella più adatta alle loro esigenze.</p>
                </div>
            </div>
        </div>
    </div>
</step-eight>