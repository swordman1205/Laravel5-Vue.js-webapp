{{--
<step-documents v-if="step === 9" @update-step="updateStep" :course="course"
                inline-template>
    <div class="container-fluid">
        <div class="row d-flex">
            <div class="registrations-steps--box col-lg-7 p-b-20">
                <div class="float-lg-right registrations-steps--box__form-container">
                    <div class="form-group">
                        <legend>Documenti richiesti</legend>
                        <div class="flex-column d-flex justify-content-start">
                            <b-form-checkbox v-for="(document, index) in documents"
                                             :key="index"
                                             v-model="documents[index].checked">
                                @{{document.name}}
                            </b-form-checkbox>
                        </div>
                    </div>

                    <div class="form-group registrations-steps__nav-button-container
">
                        @if(isset($course))
                            <button class="btn orange-gradient-button pr-5 pl-5 float-right"
                                    @click="postForm">Salva
                            </button>
                        @else
                            <button class="btn orange-gradient-button pr-5 pl-5"
                                    style="margin-right: 3rem"
                                    @click="previousStep"><i
                                        class="fas fa-chevron-left"></i> Indietro
                            </button>
                            <button class="btn orange-gradient-button float-right pr-5 pl-5"
                                    @click="postForm">Avanti <i
                                        class="fas fa-chevron-right"></i>
                            </button>
                        @endif

                    </div>
                </div>
            </div>
            <div class="container registrations-steps--info pl-lg-5 ml-lg-0 col-lg-5">
                <div class="registrations-steps--info__text-container">
                    <h5 class="mt-3 font-weight-bold">Suggestions & Info</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Consectetur
                        corporis ducimus eligendi explicabo necessitatibus nemo
                        non
                        perferendis
                        porro repudiandae similique! Aliquam aliquid commodi
                        deleniti
                        ducimus
                        eaque
                        obcaecati perspiciatis repellendus similique!</p>
                </div>
            </div>
        </div>
    </div>
</step-documents>--}}
