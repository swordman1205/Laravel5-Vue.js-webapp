<step-one v-show="step === 1" :course="course" @update-step="updateStep"
          @update-course-id="updateCourseId" :companies="{{$companies}}" :companyprop="{{$company}}"
          inline-template>
    <div class="container-fluid">
        <div class="row d-flex">
            <div class="registrations-steps--box col-lg-7 pr-lg-5 p-b-20">

                <element-clip-loader :loadingprop="false"
                                     :color="'#f4812e'"
                                     :size="'100px'"
                                     :text="'Salvataggio...'">
                </element-clip-loader>

                <div v-show="!isLoading()" class="float-lg-right registrations-steps--box__form-container">

                    <div class="form-group">
                        <legend for="course">Nome del corso
                        </legend>
                        <input class="form-control form-input registrations-steps--box__input-field-long"
                               :class="{'is-danger': errors.has('name')}"
                               id="name"
                               name="name"
                               v-model="course.name" v-validate="'required'"
                               placeholder="Es. Corso di Karate per bambini">
                    </div>
                    <p class="text-danger" v-if="errors.has('name')">@{{
                        errors.first('name') }}</p>
                    <div class="form-group">
                        <legend>Scegli uno sport
                        </legend>
                        <v-select id="sport-select" v-model="sport"
                                  label="name"
                                  value="id"
                                  placeholder="Cerca uno sport"
                                  :filterable="false" :options="sports"
                                  @search="onSearch">
                            <template slot="no-options">
                                Scrivi per selezionare lo sport nell'elenco
                            </template>
                            <template slot="option" slot-scope="option">
                                <div class="d-center">
                                    @{{ option.name }}
                                </div>
                            </template>
                            <template slot="selected-option" scope="option">
                                <div class="selected d-center">
                                    @{{ option.name }}
                                </div>
                            </template>
                        </v-select>
                    </div>
                    <div class="form-group">
                        <legend for="location">Dove di svolge?
                        </legend>
                        <vue-google-autocomplete
                                id="map"
                                ref="addressComponent"
                                class="form-control form-input registrations-steps--box__input-field-long"
                                placeholder="Via Perrone, 5 Torino"
                                @place_changed="getAddressData"
                                :types="types"
                                :value="this.course.address">
                        </vue-google-autocomplete>
                    </div>
                    <p class="text-danger"
                       v-if="errors.has('from-coursName.location')">
                        @{{
                        errors.first('from-coursName.location') }}</p>
                    <google-map
                            v-if="center"
                            :center="center"
                            :zoom="15"
                            map-type-id="terrain"
                            style="width: 100%; height: 200px">
                        <google-marker
                                :position="center"
                                :clickable="true"
                                :draggable="true"
                                @click="center=center"
                        ></google-marker>
                    </google-map>
                    <br>
                    <div class="form-group">
                        <div class="form-check">
                            <b-form-checkbox v-model="course.hasFederation">
                                <label class="form-check-label form-input">
                                    Corso supportato da una federazione?
                                </label>
                            </b-form-checkbox>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <b-form-select :disabled="!course.hasFederation"
                                           v-model="course.federationId"
                                           value-field="id"
                                           text-field="name"
                                           :options="federations"
                                           class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        @if(isset($course))
                            <button :disabled="!course.name ||!(sport && sport.id) || !course.address || !course.sportId || (course.hasFederation && !course.federationId)"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">
                                Salva
                            </button>
                        @else
                            <button :disabled="!course.name ||!(sport && sport.id) || !course.address || !course.sportId || (course.hasFederation && !course.federationId)"
                                    class="btn orange-gradient-button  registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">
                                AVANTI <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <div class="mb-3">
                        <h5 class="mt-3 font-weight-bold">Qual è il nome del tuo corso?</h5>
                        <p>Scrivi il nome del tuo corso.
                            Es. Corso di Karate per bambini</p>
                    </div>
                    <div class="mb-4">
                        <h5 class="mt-3 font-weight-bold">L’indirizzo sulla mappa è corretto?</h5>
                        <p>Se noti che l’indirizzo mostrato sulla mappa non è corretto, sposta il cursore rossa sul
                            civico esatto.</p>
                    </div>
                    <div class="mb-4">
                        <h5 class="mt-3 font-weight-bold">Fai il corso in sedi diverse?</h5>
                        <p>Se fai il corso in più sedi, inserisci l’indirizzo di una delle tue sedi.

                            Quando avrai concluso la creazione di questo corso, potrai duplicarlo e cambiare solo l’indirizzo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</step-one>