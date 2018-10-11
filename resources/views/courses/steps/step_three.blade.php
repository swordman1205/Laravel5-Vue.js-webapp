<step-three v-if="step === 3" @update-step="updateStep" :course="course"
            :audience="audience"
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
                        <legend>Età dei partecipanti?</legend>
                        <div class="form-inline">
                            <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6 no-padding aligned-items-center m-b-10">
                                <span class="registrations-steps--box__small-label-left">Da</span>
                                <input type="number"
                                       class="form-control form-input registrations-steps--box__input-field-short registrations-steps--box__form-container__small-input"
                                       :class="{'is-danger': errors.has('start age')}"
                                       v-model="course.startAge" name="from age"
                                       v-validate="'required'"
                                       placeholder="es. 6">
                            </div>
                            <div class="col-lg-6 col-xs-6 col-sm-6 col-md-6 no-padding aligned-items-center m-b-10">
                                <span class="registrations-steps--box__small-label-center">a</span>
                                <input type="number"
                                       class="form-control form-input registrations-steps--box__input-field-short registrations-steps--box__form-container__small-input"
                                       v-model="course.endAge"
                                       name="to age"
                                       v-validate="'required'"
                                       placeholder="es. 10">
                                <span class="mobile-hidden m-l-20">anni</span>
                            </div>
                            <small>(Il numero inserito nel secondo campo deve essere maggiore del primo)</small>
                        </div>
                        <p class="text-danger"
                           v-if="errors.has('from age')">@{{
                            errors.first('from age') }}</p>
                        <p class="text-danger" v-if="errors.has('to age')">
                            @{{
                            errors.first('to age') }}</p>
                    </div>
                    <div class="form-group">
                        <legend>Livello del corso?</legend>
                        <div class="col-lg-12 col-xs-12 no-padding">
                            <b-button v-for="(level, index) in levels"
                                      :key="index"
                                      class="form-button-rounded form-button-level col-lg-4 col-xs-12 no-padding"
                                      v-bind:class="{'form-button-selected' : course.selectedLevel === level}"
                                      @click="selectLevel(level)">@{{ level }}
                            </b-button>
                        </div>
                    </div>
                    <div class="form-group">
                        <legend>Corso:</legend>
                        <div class="flex-column justify-content-start">
                            <b-form-checkbox v-model="course.is_for_male">
                                Maschile
                            </b-form-checkbox>
                            <b-form-checkbox v-model="course.is_for_female">
                                Femminile
                            </b-form-checkbox>
                        </div>
                        <div class="flex-column justify-content-start">
                            <b-form-checkbox v-model="audience.forAbleBodied">
                                Normodotati
                            </b-form-checkbox>
                            <b-form-checkbox v-model="audience.forDisabled">
                                Disabili
                            </b-form-checkbox>
                        </div>
                    </div>
                    <div class="form-group"
                         :class="{'form-group-disabled': !audience.forDisabled}">
                        <legend>Disabilità supportate</legend>
                        <div class="flex-column d-flex justify-content-start">
                            <b-form-checkbox :key="index" :value="disability.id"
                                             v-model="audience.checkedDisabilities"
                                             :disabled="!audience.forDisabled"
                                             v-for="(disability, index) in disabilities">
                                @{{disability.name}}
                            </b-form-checkbox>
                        </div>
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">
                        @if(isset($course))
                            <button :disabled="!course.startAge || !course.endAge || !course.selectedLevel || (!audience.forAbleBodied && !audience.forDisabled) ||
                                    (!audience.forAbleBodied && audience.forDisabled && audience.checkedDisabilities.length == 0)"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i>
                                Indietro
                            </button>

                            <button :disabled="!course.startAge || !course.endAge || !course.selectedLevel || (!audience.forAbleBodied && !audience.forDisabled) ||
                                    (!audience.forAbleBodied && audience.forDisabled && audience.checkedDisabilities.length == 0)"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right "
                                    @click="postForm">AVANTI <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif


                        <span v-if="message">@{{ message }}</span>
                    </div>
                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">L’età è importantissima per capire se è il corso giusto!</h5>
                    <p> Non inserire fasce di età troppo ampie!
                        Soprattutto nel caso di minori, i genitori hanno bisogno di capire se questo
                        è il corso adatto all’età dei propri figli.</p>
                    <p>Al termine dell’inserimento potrai duplicare il corso e modificare solo le informazioni che cambiano.</p>
                    <h5 class="mt-3 font-weight-bold">Supporti disabilità?</h5>
                    <p>Il corso è rivolto solo a normodati, solo a diversamente abili o prevede l’integrazione tra le due categorie?</p>
                    <p>Cliccando su “Disabili” o “Normodotati e disabili”, puoi scegliere quale tipo di disabilità il tuo corso supporta.</p>
                </div>
            </div>
        </div>
    </div>
</step-three>