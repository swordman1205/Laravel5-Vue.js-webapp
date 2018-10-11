<div>
    <b-container>
        <b-row class="w-100">
            <b-col>
                <h2 class="font-weight-bold">Profilo Società</h2>
            </b-col>
            <b-col offset-lg="4" class="w-100 w-md-init">

                <b-button class="registration-navbar--button font-weight-bold w-100"
                          href="{{route('company.show',$company->slug)}}">ANTEPRIMA PAGINA
                </b-button>

            </b-col>
        </b-row>
        <b-row class="mt-4">
            <b-col class="border-left border-right border-top pt-3 pb-2 pl-5"><h4>Campi Obbligatori</h4></b-col>
        </b-row>
        <b-row class="border pt-2 pb-2">
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Ragione
                        Sociale</label><i class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.name}"
                                       id="name"
                                       name="name"
                                       v-model="company.name"
                                       placeholder="es. Palestra Karate Miura Torino"/>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="public-name"
                                                                        class="pl-2 font-weight-bold mt-2">Nome
                        Pubblico</label><i class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       id="public-name"
                                       name="public name"
                                       v-model="company.publicName"
                                       placeholder="es. Karate Miura"/>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Tipologia</label><i
                            class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6">
                    <b-form-select
                            v-model="company.typologyId"
                            :class="{'is-danger': validationErrors.typology}"
                            value-field="id"
                            text-field="name"
                            :options="typologies"
                            class="w-100 w-md-50 form-control">
                        <template slot="first">
                            <option :value="null" disabled>es. Società</option>
                        </template>
                    </b-form-select>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Forma
                        Giuridica</label><i class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6">
                    <b-form-select
                            v-model="company.legalFormId"
                            :class="{'is-danger': validationErrors.legalForm}"
                            value-field="id"
                            text-field="name"
                            :options="legalForms"
                            class="w-100 w-md-50 form-control">
                        <template slot="first">
                            <option :value="null" disabled>es. A.S.D.</option>
                        </template>
                    </b-form-select>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="email" class="pl-2 font-weight-bold mt-2">Email
                        Società</label><i class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.email}"
                                       id="email"
                                       name="email"
                                       type="email"
                                       v-model="company.email"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="phone" class="pl-2 font-weight-bold mt-2">Telefono</label><i
                            class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.phoneNumber}"
                                       id="phone"
                                       name="phone number"
                                       type="text"
                                       v-model="company.phoneNumber"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="fiscal-code"
                                                                        class="pl-2 font-weight-bold mt-2">Codice
                        Fiscale</label><i class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       :class="{'is-danger': validationErrors.fiscalCode}"
                                       id="fiscal-code"
                                       name="fiscal code"
                                       v-model="company.fiscalCode"/>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Indirizzo
                        Sede Legale</label><i class="ml-3 fas fa-lock"></i></b-col>
                <b-col cols="12" md="6">
                    <vue-google-autocomplete
                            id="map"
                            class="form-control form-input"
                            :class="{'is-danger': validationErrors.address}"
                            placeholder="es. Via dai Pensieri 14, 10153 Torino"
                            @place_changed="getAddressData"
                            :value="company.address"
                            :types="types">
                    </vue-google-autocomplete>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="promotion-agency"
                                                                        class="pl-2 font-weight-bold mt-2">Ente
                        di Promozione</label><i class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       id="promotion-agency"
                                       name="promotion agency"
                                       v-model="company.promotionAgency"
                                       placeholder="es."/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Logo</label><i
                            class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6">
                    <file-upload inline-template
                                 input-class="form-control"
                                 :max-size="imageMaxSize"
                                 :default-preview="company.logoPath"
                                 @size-exceeded="onSizeExceeded"
                                 @file="onFile">
                        <div class="form-inline">
                            <div v-if="previewImage !== ''" class="position-relative">
                                <img :src="previewImage"
                                     class="form-image-preview-large form-solid-border"/>
                                <span @click="removeImage()" class="dismiss-image-btn">x</span>
                            </div>
                            <label for="file-upload">
                                <div v-if="previewImage === ''"
                                     class="form-image-preview-large form-dashed-border">
                                    <div>
                                        <image width="65px"
                                               height="52px"
                                               src="/images/photo-icon.svg"></image>
                                    </div>
                                </div>
                                <input id="file-upload"
                                       style="display:none"
                                       type="file"
                                       @change="onChange"
                                       accept="image/png,image/jpeg"/>
                            </label>
                        </div>
                    </file-upload>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="description"
                                                                        class="pl-2 font-weight-bold mt-2">Descrizione
                        Società</label><i class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6">
                  <textarea
                          rows="5"
                          id="description"
                          maxlength="600"
                          placeholder="Inserisci una breve descrizione della tua SS"
                          type="text" class="form-control form-input"
                          v-model="company.description"
                          name="description"></textarea>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
        </b-row>
        <b-row class="mt-4">
            <b-col class="border-left border-right border-top pt-3 pb-2 pl-5 mt-4"><h4>Facoltativo</h4></b-col>
        </b-row>
        <b-row class="border pt-2 pb-2">
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="vat-number"
                                                                        class="pl-2 font-weight-bold mt-2">Partita
                        Iva
                    </label><i class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       id="vat-number"
                                       name="vat number"
                                       v-model="company.vatNumber"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="social-share"
                                                                        class="pl-2 font-weight-bold mt-2">Quota
                        Sociale
                    </label><i class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6"><input class="form-control form-input mb-2"
                                       id="social-share"
                                       name="social share"
                                       v-model="company.socialShare"/>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Federazione</label><i
                            class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6">
                    <b-form-select
                            v-model="company.federationId"
                            value-field="id"
                            text-field="name"
                            :options="federations"
                            class="w-100 w-md-50 form-control">
                        <template slot="first">
                            <option :value="null" disabled>es. FIV</option>
                        </template>
                    </b-form-select>
                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="name" class="pl-2 font-weight-bold mt-2">Fotogallery</label><i
                            class="ml-3 fas fa-lock" style="visibility: hidden"></i></b-col>
                <b-col cols="12" md="6">
                    <files-upload v-if="company.gallery"
                                  inline-template
                                  input-class="form-control"
                                  :max-size="imageMaxSize"
                                  :file-paths="company.gallery"
                                  @size-exceeded="onSizeExceeded"
                                  @file="onFileGallery"
                                  @remove-file-index="removeFileGallery">
                        <div class="form-inline">
                            <label for="file-gallery-upload">
                                <div class="form-image-preview-large form-dashed-border">
                                    <div class="">
                                        <image src="/images/photo-gallery-icon.svg"></image>
                                    </div>
                                </div>
                                <input id="file-gallery-upload"
                                       style="display:none"
                                       type="file"
                                       @change="onChange"
                                       accept="image/png,image/jpeg"/>
                            </label>
                            <div class="ml-5" v-if="previewImages.length < 1">
                                <span>Dimensioni minime: 300 x 300px</span>
                                <br>
                                <span>Formati supportati: JPEG, PNG</span>
                            </div>
                            <div class="form-gallery">
                                <div class="position-relative form-gallery-item col-xs-3"
                                     v-for="(previewImage, index) in previewImages"
                                     :key="index">
                                    <img :src="previewImage"
                                         class="form-image-preview-small"/>
                                    <span @click="removeImage(index)" class="dismiss-image-btn">x</span>
                                </div>

                            </div>
                        </div>
                    </files-upload>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="statute"
                                                                        class="pl-2 font-weight-bold mt-2">Statuto</label><i
                            class="ml-3 fas fa-lock" style="visibility: hidden"></i>
                    <br>
                    <label for="statute-file" class="text-uppercase font-weight-bold cursor-pointer text-primary">CARICA
                        FILE<i
                                class="ml-1 fas fa-upload"></i>
                        <input id="statute-file" type="file" @change="onStatuteFileChange" style="display:none">
                    </label><i class="ml-3 fas fa-lock"
                               style="visibility: hidden"></i>
                </b-col>
                <b-col cols="6">
                  <textarea
                          :disabled="statuteFileName"
                          rows="5"
                          placeholder="Inserisci lo statuto della tua SS"
                          type="text" class="form-control form-input mb-2 mt-2"
                          v-model="company.statute.text"
                          id="statute"
                          name="statute"></textarea>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
            <b-row class="pt-2 w-100">
                <b-col cols="12" md="3" offset-md="1" class="text-left text-md-right"><label for="statute"
                                                                        class="pl-2 font-weight-bold mt-2">Privacy
                        Policy</label><i
                            class="ml-3 fas fa-lock" style="visibility: hidden"></i>
                    <br>
                    <label for="privacy-policy-file"
                           class="text-uppercase font-weight-bold cursor-pointer text-primary">CARICA FILE<i
                                class="ml-1 fas fa-upload"></i>
                        <input id="privacy-policy-file" type="file" @change="onPrivacyPolicyFileChange"
                               style="display:none">
                    </label><i class="ml-3 fas fa-lock"
                               style="visibility: hidden"></i>
                </b-col>
                <b-col cols="12" md="6">
                  <textarea
                          :disabled="privacyPolicyFileName"
                          rows="5"
                          placeholder="Inserisci la privacy policy della tua SS"
                          type="text" class="form-control form-input mb-2 mt-2"
                          v-model="company.privacyPolicy.text"
                          id="privacy-policy"
                          name="privacy policy"></textarea>

                </b-col>
                <b-col cols="12" md="3"></b-col>
            </b-row>
        </b-row>
        <b-row class="mt-5 mb-5">
            <b-button class="registration-navbar--button font-weight-bold" style="width: 175px;" @click="postForm">
                <span v-if="message">@{{ message }}</span>
                <span v-else>SALVA</span>
            </b-button>
        </b-row>
    </b-container>
</div>
