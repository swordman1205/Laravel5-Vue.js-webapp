<step-five v-if="step === 5" @update-step="updateStep" :course="course"
          :description="description"
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
                        <legend>Descrizione corso</legend>
                        <textarea
                                rows="5"
                                maxlength="600"
                                placeholder="es. Il corso di pallavolo si divide in 3 fasi etc..."
                                type="text" class="form-control form-input"
                                :class="{'is-danger': errors.has('description') || textValidationError}"
                                v-model="description.text" name="description"
                                v-validate="'required'"></textarea>
                    </div>
                    <div class="form-group">
                        <legend>Foto copertina</legend>
                        <div>
                            <file-upload inline-template
                                         input-class="form-control"
                                         :max-size="imageMaxSize"
                                         :default-preview="description.filePath"
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
                        </div>
                    </div>
                    <div class="form-group">
                        <legend>Gallery</legend>
                        <files-upload inline-template
                                      input-class="form-control"
                                      :max-size="imageMaxSize"
                                      :file-paths="description.gallery"
                                      @size-exceeded="onSizeExceeded"
                                      @file="onFileGallery"
                                      @remove-file-index="removeFileGallery">
                            <div class="form-inline ">
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
                    </div>
                    <div class="form-group registrations-steps__nav-button-container">

                        @if(isset($course))
                            <button :disabled="!description.text"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button registrations-steps--box__form-container__nav-button"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button :disabled="!description.text"
                                    class="btn orange-gradient-button registrations-steps--box__form-container__nav-button float-right"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                    </div>

                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">Abbiamo quasi finito!</h5>
                    <p> Inserisci una breve descrizione del tuo corso e carica le foto della tua struttura o del corso stesso.</p>
                    <h6 class="mt-3 font-weight-bold">Le foto sono importantissime!</h6>
                    <p>I corsi più cliccati e prenotati, sono quelli con le foto!</p>

                    <div class="col-md-12 course_text-warning">
                        <p> <span class="text-danger">Attenzione!</span>
                            Assicurati di inserire solo foto di persone di cui hai un consenso.
                            Non inserire foto di minori di cui non sei certo di avere il consenso.</p>

                        <p>OrangoGo si riserva di eliminare senza preavviso foto ritenute
                            offensive o che non rispettano le regole della piattaforma.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</step-five>