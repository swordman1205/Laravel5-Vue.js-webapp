<step-six v-if="step === 6" @update-step="updateStep"
          :course="course" :lesson="lesson"
          inline-template v-cloak>
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
                        <legend>Come funziona la prima lezione?</legend>
                        <div>
                            <b-form-radio-group stacked
                                                v-model="lesson.isFreeLesson"
                                                :options="lessonPriceOptions">
                            </b-form-radio-group>
                            <input placeholder="es. 15 euro"
                                   :disabled="lesson.isFreeLesson"
                                   type="number" class="form-control"
                                   :class="{'form-input': !lesson.isFreeLesson,'is-danger': (validationErrors.priceError || errors.has('price')) && !lesson.isFreeLesson}"
                                   v-model="lesson.price" name="price"
                                   v-validate="'required'"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <legend>Quando si può prenotare la prima lezione?</legend>
                        <div>
                            <b-form-radio-group stacked
                                                v-model="lesson.isDuringCourseTime"
                                                :options="lessonDurationOptions">
                            </b-form-radio-group>
                        </div>
                    </div>
                    <div :class="{'form-group-disabled': lesson.isDuringCourseTime}">

                        <span>ORARIO LEZIONE</span>
                        <div class="form-inline">
                            <span style="margin-right: 1rem">Il</span>
                            <input v-if="lesson.isDuringCourseTime"
                                   class="form-control" disabled>
                            <datepicker id="datePickerForStepSix" v-else
                                        v-model="lesson.specificDate.date" class="form-datepicker" :format="dateFormatter"
                                        :class="{'is-danger': validationErrors.dateTimeError}">
                            </datepicker>
                            <span style="margin-left: 1rem; margin-right: 1rem">alle</span>
                            <input v-if="lesson.isDuringCourseTime"
                                   placeholder="es. 15:00"
                                   class="form-control" disabled>
                            <timepicker style="width: 100px;" v-else
                                        :class="{'is-danger': validationErrors.dateTimeError,
                                                                'form-timepicker-border': !validationErrors.dateTimeError}"

                                        v-model="lesson.specificDate.time">
                            </timepicker>
                        </div>

                    </div>
                    <br>

                    <div class="form-group">
                        <div class="flex-column d-flex justify-content-start">
                            <legend>Per la prima lezione consiglio di portare</legend>
                            <textarea
                                    rows="5"
                                    placeholder="es. Accappatoio, Ciabatte, etc..."
                                    type="text" class="form-control"
                                    :class="{'form-input': lesson.hasEquipments,'is-danger': (validationErrors.equipmentError || errors.has('equipments')) && lesson.hasEquipments}"
                                    v-model="lesson.equipments"
                                    name="equipments"
                                    v-validate="'required'"></textarea>
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
                    <h5 class="mt-3 font-weight-bold">Lezione di prova</h5>
                    <p>Tramite la pagina del tuo corso, le persone potranno prenotare giorno e ora della prima lezione.</p>
                    <p>Riceverai una mail con i dati della prenotazione e potrai ricontattare lo sposrtivo
                        se la data richiesta non fosse disponibile.</p>
                    <p>Se la <strong>prima lezione è gratuita</strong> accoglierai lo sportivo che presenterà la prenotazione.</p>
                    <p>Se la <strong>prima lezione è a pagamento</strong>, chi effettua la prenotazione pagherà in loco la quota da te indicata.</p>
                </div>
            </div>
        </div>
    </div>
</step-six>