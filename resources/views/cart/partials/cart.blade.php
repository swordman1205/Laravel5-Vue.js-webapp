<cart :cartelementsprop="{{$cartElements}}" :totalpriceprop="{{$totalPrice}}" :sportmenprop="{{$friends}}"
      inline-template v-cloak>
    <div class="app">
        <b-container class="payment-tabs__cart">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">OGGETTO NEL CARRELLO</th>
                    <th scope="col">TIPOLOGIA</th>
                    <th scope="col">PREZZO</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(cartElement, cartElementIndex) in cartElements" :key="cartElementIndex"
                    class="mt-4 border-bottom">
                    <td scope="row" style="text-align: left">
                        <b-row>
                            <b-col>
                                <div class="home-course-card__image">
                                    <img class="course-image" :src="cartElement.buyable.course.course_image"/>
                                </div>
                            </b-col>
                            <b-col>
                                <h3 class="font-weight-bold">@{{cartElement.buyable.course.seo_name_short}}</h3>
                                <span>Per @{{cartElement.buyable.course.recipient}} tra @{{cartElement.buyable.course.start_age}} - @{{ cartElement.buyable.course.end_age }} anni</span>
                                <span class="p-l-10">{recensioni}</span>
                            </b-col>
                        </b-row>
                    </td>
                    <td>
                        <span v-if="isLessonPackage(cartElement.buyable_type)">Pacchetto di @{{cartElement.buyable.n_lessons}} lezioni</span>
                        <span v-if="isSubscription(cartElement.buyable_type)">Abbonamento di @{{cartElement.buyable.subscription.name}}</span>
                    </td>
                    <td>@{{cartElement.buyable.price}} €</td>
                    <td>
                        <b-col>
                            <b-row class="text-right w-100 justify-content-end">
                                <div class="pl-3 pr-3">
                            <span class="text-uppercase" @click="modalShow = !modalShow" style="cursor: pointer;">rimuovi<i
                                        class="company-remove-icon ml-2 fas fa-times"></i></span></span>

                                </div>
                                <b-modal v-model="modalShow" id="getCartElementId(cartElement.id)" @ok="remove(cartElement.id, cartElementIndex)">
                                    Sei sicuro di voler eliminare questo corso?
                                </b-modal>
                            </b-row>
                        </b-col>
                    </td>
                </tr>
                </tbody>
            </table>
            <b-row>
                <div class="w-100">
                    <span class="text-uppercase float-right">
                        Totale: <span class="p-l-30" style="font-size: 32px"><b>@{{ total }} €</b></span>
                    </span>
                </div>
            </b-row>
            <b-row>
                <div class="w-100">
                    <div class="float-right">
                        <b-button class="white-solid-button" href="{{route('home')}}">Continua la
                            navigazione
                        </b-button>
                        <b-button class="m-l-20 orange-solid-button" @click="goAhead(2)">Avanti</b-button>
                    </div>
                </div>
            </b-row>

        </b-container>
    </div>
</cart>

