<step-four v-if="step === 4" @update-step="updateStep" :course="course"
           :audience="audience"
           :responsible="responsible"
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
                        <legend>Responsabile del corso</legend>
                        <b-row>
                            <b-col cols="9" class="no-padding-left">
                                <input type="text"
                                       class="form-control form-input registrations-steps--box__input-field-short"
                                       id="responsabile-input"
                                       :class="{'is-danger': errors.has('name responsible') || validationError.name}"
                                       v-model="responsible.name"
                                       name="name responsible">
                            </b-col>
                        </b-row>

                        <p class="text-danger"
                           v-if="errors.has('name responsible')">@{{
                            errors.first('name responsible') }}</p>
                        <br>
                       <!-- <b-form-checkbox v-model="responsible.hasEmail">
                            Vuoi invitare il responsabile a gestire il corso?
                        </b-form-checkbox>
                        <br>
                        <input v-if="responsible.hasEmail" type="email"
                               placeholder="email del responsabile"
                               class="form-control form-input registrations-steps--box__input-field-short"
                               :class="{'is-danger': errors.has('email') || validationError.email}"
                               v-model="responsible.email" name="email"
                               v-validate="'required|email'">
                        <p class="text-danger" v-if="errors.has('email')">@{{
                            errors.first('email') }}</p>-->
                    </div>

                    <div class="form-group">
                        <legend>Servizi disponibili</legend>
                        <div class="flex-column d-flex justify-content-start">
                            <b-form-checkbox :key="index" :value="service.id"
                                             v-model="audience.checkedServices"
                                             v-for="(service, index) in services">
                                @{{service.name}}
                            </b-form-checkbox>
                        </div>
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">

                        @if(isset($course))
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    :disabled="(!responsible.name || responsible.hasEmail && !responsible.email)"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    style="margin-right: 3rem"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    :disabled="(!audience.forAbleBodied && !audience.forDisabled) ||
                                (!audience.forAbleBodied && audience.forDisabled && audience.checkedDisabilities.length == 0)"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif


                    </div>
                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">C’è un referente/allenatore che si occupa di gestire il corso?</h5>
                    <p>Inserisci il suo nome e, se la possiedi, una foto.</p>
                    <h6 class="mt-3 font-weight-bold">Perchè inserire la foto?</h6>
                    <p>Agli sportivi piace vedere il volto di chi sarà il loro istruttore.</p>
                    <h6 class="mt-3 font-weight-bold">Cosa vuol dire gestire il corso?</h6>
                    <p>Il referente potrà modificare il corso e rispondere agli sportivi.</p>
                </div>
            </div>
        </div>
    </div>
</step-four>