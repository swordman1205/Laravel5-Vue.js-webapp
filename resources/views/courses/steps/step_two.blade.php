<step-two v-if="step === 2" @update-step="updateStep" :course="course"
          :day-names="dayNames"
          :course-days="courseDays"
          inline-template>
    <div class="container-fluid">
        <div class="row d-flex">
            <div class="registrations-steps--box col-lg-7 p-b-20"
                 data-vv-scope="from-coursName">
                <element-clip-loader :loadingprop="false"
                                     :color="'#f4812e'"
                                     :size="'100px'"
                                     :text="'Salvataggio...'">
                </element-clip-loader>

                <div v-show="!isLoading()" class="float-lg-right registrations-steps--box__form-container">
                    <div class="form-group">
                        <legend>Durata periodo del corso</legend>
                        <div class="form-inline">
                            <div class="col-lg-6 col-xs-12 col-sm-12 col-md-12 aligned-items-center m-b-10">
                                <span class="registrations-steps--box__small-label-left">Da</span>
                                <datepicker id="startDatePicker"  :format="dateFormatter"
                                             :language="it"
                                        class="registrations-steps--box__form-container__small-input form-datepicker"
                                        v-model="course.startDate"></datepicker>
                            </div>
                            <div class="col-lg-6 col-xs-12 col-sm-12 col-md-12 aligned-items-center m-b-10">
                                <span class="registrations-steps--box__small-label-center">A</span>
                                <datepicker id="endDatePicker" :format="dateFormatter"
                                            :language="it"
                                        class="form-datepicker registrations-steps--box__form-container__small-input"
                                        v-model="course.endDate"
                                        :disabled-dates="disabledDates"></datepicker>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <legend>In quali giorni?</legend>
                        <b-button class="form-day-box-button"
                                  v-for="(day, index) in dayNames"
                                  :key="index"
                                  @click="addTime(day, index)">@{{
                            day.shortName }}
                        </b-button>
                        <br>
                        <div>
                            <div v-for="(day, index) in courseDays"
                                 :key="index">
                                <div class="form-inline registrations-steps--box__days-hour-container">
                                    <span class="form-day-box-fill">@{{ day.shortName }}</span>

                                    <timepicker :class="{'is-danger': day.validationError }"
                                            v-model="day.startTime">
                                    </timepicker>

                                    <timepicker :class="{'is-danger': day.validationError }"
                                            v-model="day.endTime">
                                    </timepicker>

                                    <span @click="removeTime(index)"
                                          style="cursor: pointer;"><img
                                                src="/images/close-icon.svg"
                                                alt=""></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group registrations-steps__nav-button-container">
                        @if(isset($course))
                            <button :disabled="!dateValidation || courseDays.length == 0"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i>
                                Indietro
                            </button>

                            <button :disabled="!dateValidation || courseDays.length == 0"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                        <span v-if="message">@{{ message }}</span>
                    </div>
                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <div class="mb-4">
                        <h5 class="mt-3 font-weight-bold">Quando inizia e quando termina il corso?</h5>
                        <p>Seleziona la data di inizio e di fine del tuo corso.</p>
                        <p>Es. Il corso inizia il 15 Settembre e termina il 30 Maggio.</p>

                        <p>Famiglie e sportivi ti possono conttare per questo corso da subito, anche se inserisci una
                            data di inizio futura.</p>

                        <p>Il corso si disattiverà automaticamente nella data di termine da te indicata.</p>
                    </div>
                    <div class="mb-4">
                        <h5 class="mt-3 font-weight-bold">
                            Giorni e orari dei tuoi corsi? </h5>
                        <p>Seleziona i giorni in cui fai il corso e scrivi gli orari.</p>

                        <p>Per inserire 2 o più fasce orarie nello stesso giorno, clicca due o più volte sul giorno
                            della settimana.</p>

                        <p>Es. Se il corso di karate per bambini è
                            Martedì dalle 17:00 alle 18:30 e anche dalle 18:00 alle 19:30, clicca 2 volte sul
                            Martedì.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</step-two>