<friend-form :sportmenprop="{{$friends}}" :cartelementsprop="{{$cartElements}}" inline-template v-cloak>
    <div class="container payment-tabs__sports-man">
        <div class="row">
            <div class="col-lg-6 col-sm-12 col-xs-12 no-padding payment-tabs__sports-man__product-summary">
                <div v-for="(cartElement, cartElementIndex) in cartElements" :key="cartElementIndex" class="row">
                    <div class="col-lg-4 col-xs-12 col-sm-12">
                        <div class="home-course-card__image">
                            <img class="course-image" :src="cartElement.buyable.course.course_image"/>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xs-12 col-sm-12 p-t-20">
                        <h3 class="font-weight-bold">@{{cartElement.buyable.course.seo_name_short}}</h3>
                        <span>Per @{{cartElement.buyable.course.recipient}} tra @{{cartElement.buyable.course.start_age}} - @{{ cartElement.buyable.course.end_age }} anni</span>
                        <span class="p-l-10">{recensioni}</span>
                        <span v-if="isLessonPackage(cartElement.buyable_type)">Pacchetto di @{{cartElement.buyable.n_lessons}} lezioni</span>
                        <span v-if="isSubscription(cartElement.buyable_type)">Abbonamento di @{{cartElement.buyable.subscription.name}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 col-xs-12 payment-tabs__sports-man__form-container">
                <div>
                    <b-form-group>
                        <b-form-select :disabled="!friends || friends.length < 1"
                                       v-model="selectedFriend"
                                       value-field="id"
                                       text-field="name"
                                       :options="friends"
                                       class="form-input"/>
                    </b-form-group>
                    <hr>
                    <div class="text-center">
                        <span class="text-uppercase"><b>dati prenotazione</b></span>
                    </div>

                    <b-form-group>
                        <b-form-input type="text"
                                      v-model="sportsman.firstName"
                                      placeholder="Nome"
                                      name="first_name"
                                      class="form-input">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <b-form-input type="text"
                                      v-model="sportsman.lastName"
                                      placeholder="Cognome"
                                      name="last_name"
                                      class="form-input">
                        </b-form-input>
                    </b-form-group>
                    <p class="font-weight-bold mb-1">Data di nascita</p>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="flex-fill mr-2">
                            <b-form-group>
                                <b-form-select v-model="sportsman.day"
                                               :options="days"
                                               name="day"
                                               class="form-input"/>
                            </b-form-group>
                        </div>
                        <div class="flex-fill mx-2">
                            <b-form-group>
                                <b-form-select v-model="sportsman.month"
                                               :options="months"
                                               name="month"
                                               class="form-input"/>
                            </b-form-group>
                        </div>
                        <div class="flex-fill ml-2">
                            <b-form-group>
                                <b-form-select v-model="sportsman.year"
                                               :options="years"
                                               name="year"
                                               class="form-input"/>
                            </b-form-group>
                        </div>
                    </div>
                    <b-form-group>
                        <b-form-input type="email"
                                      v-model="sportsman.email"
                                      placeholder="Email"
                                      name="email"
                                      class="form-input">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <b-form-checkbox v-model="isTermAccepted">Si accetta il trattamento dei dati secondo la Privacy
                            Policy ai soli fini di erogazione del servizio come qui esplicato
                        </b-form-checkbox>
                    </b-form-group>
                    <b-form-group>
                        <button class="orange-solid-button w-100" @click="goAhead(3)">continua</button>
                    </b-form-group>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
</friend-form>