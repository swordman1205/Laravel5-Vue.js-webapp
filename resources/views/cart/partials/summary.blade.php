@extends('layouts.main')
@section('title', 'Carrello')
@section('content')

    <payment-summary inline-template v-cloak :userprop="{{$user}}" :purchaseprop="{{$purchase}}"
                     :purchaseelementsprop="{{$purchaseElements}}">
        <div class="container payment-tabs__summary">
            <b-row>
                <h2><b>Grazie per il tuo ordine!</b></h2>
            </b-row>
            <hr>
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-xs-12 no-padding payment-tabs__summary__product-summary">
                    <div v-for="(purchaseElement, purchaseElementIndex) in purchaseElements" :key="purchaseElementIndex"
                         class="row">
                        <div class="col-lg-4 col-xs-12 col-sm-12">
                            <div class="home-course-card__image">
                                <img class="course-image" :src="purchaseElement.buyable.course.course_image"/>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xs-12 col-sm-12 p-t-20">
                            <h3 class="font-weight-bold">@{{purchaseElement.buyable.course.seo_name_short}}</h3>
                            <span>Per @{{purchaseElement.buyable.course.recipient}} tra @{{purchaseElement.buyable.course.start_age}} - @{{ purchaseElement.buyable.course.end_age }} anni</span>
                            <span class="p-l-10">{recensioni}</span>
                            <span v-if="isLessonPackage(purchaseElement.buyable_type)">Pacchetto di @{{purchaseElement.buyable.n_lessons}} lezioni</span>
                            <span v-if="isSubscription(purchaseElement.buyable_type)">Abbonamento di @{{purchaseElement.buyable.name}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-xs-12 payment-tabs__summary__code-container">
                    <p style="font-size: 21px"><b>Codice acquisto: </b><span>@{{ purchase.purchase_code }}</span></p>
                    <p style="color: lightgrey;">Conserva il codice e presentalo alla prima lezione</p>
                    {{--<hr class="light-divider">
                    <div class="border-top p-t-15">
                        <button class="white-solid-button">scarica fattura</button>
                        <p style="color: lightgrey;" class="m-t-5">Puoi scaricare la fattura quando vuoi dalla tua sezione
                            “I miei acquisti”.</p>
                    </div>--}}
                    {{--<div class="border-top p-t-15">
                        <h4><b>Invita un amico</b></h4>
                        <p style="color: lightgrey;">Inserisci la mail di un amico ed invitalo a partecipare con
                            te al corso che hai scelto.</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-input clickable" placeholder="Email" aria-describedby="basic-addon2" >
                            <div class="input-group-append">
                                <button class="input-right-button-orange-gradient" type="button"><i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
            <hr>
            <div class="float-right">
                <b-button href="{{route('profile')}}" class="orange-gradient-button">vai al tuo profilo</b-button>
            </div>
        </div>
    </payment-summary>

@endsection
