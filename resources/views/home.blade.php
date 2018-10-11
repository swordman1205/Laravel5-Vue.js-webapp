@extends('layouts.main')
@section('title', 'Home')
@section('content')
    <homepage :courses="{{$courses}}" inline-template v-cloak>
        <div>
            <page-clip-loader :loading="true"
                              :color="'#f4812e'"
                              :size="'100px'"
                              :text="'Geolocalizzazione...'">
            </page-clip-loader>
            <b-container fluid class="homepage">
                <div class="homepage__header">
                    <p class="homepage__header__site-name">ORANGOGO</p>
                    <h2 class="homepage__header__title font-weight-bold">IL MOTORE DI RICERCA DEGLI SPORT</h2>
                    <b-row>
                        <b-col cols="12" sm="8" offset-sm="2">
                            <home-sport-search placeholder="Cerca per sport o per luogo"
                                               url="/corsi/search"></home-sport-search>
                        </b-col>
                    </b-row>
                </div>
                <div id="close_courses" class="homepage__section homepage__section--courses hidden">
                    <h4 class="homepage__section__title font-weight-bold">I corsi vicino a te</h4>
                    <home-carousel :items="orderedCourseList" :count-prop="4"></home-carousel>
                </div>
                <div class="homepage__section homepage__section--courses">
                    <h4 class="homepage__section__title font-weight-bold">
                        Gli ultimi corsi inseriti</h4>
                    <home-carousel :items="courseList" :count-prop="4" v-if="courseList.length > 0">
                        {{-- <template slot-scope="item">
                             <home-course-card :course="item">
                             </home-course-card>
                         </template>--}}
                    </home-carousel>
                </div>
                <p id="howworks"></p>
                <div class="homepage__section homepage__section--how-it-works">
                    <h1 class="homepage__section__title font-weight-bold">Come funziona OrangoGo</h1>
                    <how-orango-works-breadcrumb></how-orango-works-breadcrumb>
                </div>
                <div class="homepage__section homepage__section--testimonial">
                    <h4 class="homepage__section__title font-weight-bold">Dicono di noi</h4>
                    <b-row class="pl-0 pl-lg-5 pt-3">
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Grazie ad Orangogo ho
                                        trovato lo sport perfetto per mia figlia</p>
                                    <p class="homepage__section--testimonial__block__name">AnnaCarla - Brescia</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Utile e comodo, ho
                                        scoperto un corso di yoga sotto casa mia con un insegnante bravissimo</p>
                                    <p class="homepage__section--testimonial__block__name">Antonella - Milano</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Finalmente qualcuno
                                        che fa qualcosa per il mondo dello sport</p>
                                    <p class="homepage__section--testimonial__block__name">Giovanni - Napoli</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Filtro Disabilità:
                                        grazie per aver pensato anche a chi, come mio figlio, non ha la possibilità di
                                        fare sport in tutte le strutture</p>
                                    <p class="homepage__section--testimonial__block__name">Sandra - Genova</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Molte associazioni
                                        applicano il 10% di sconto sull'abbonamento se prenoti la prima prova tramite
                                        OrangoGo</p>
                                    <p class="homepage__section--testimonial__block__name">Cesare - Roma</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                        <b-col cols="12" sm="6" lg="4">
                            <div class="homepage__section--testimonial__block">
                                <span class="homepage__section--testimonial__block__left-quote">&ldquo;</span>
                                <div>
                                    <p class="homepage__section--testimonial__block__description">Comodo! Scegli il
                                        corso che ti interessa e prenoti la prima prova con un click</p>
                                    <p class="homepage__section--testimonial__block__name">Francesco - Firenze</p>
                                </div>
                                <span class="homepage__section--testimonial__block__right-quote">&rdquo;</span>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <div class="homepage__section homepage__section--search-company">
                    <h4 class="homepage__section__title font-weight-bold">Sei una società sportiva?</h4>
                    <div class="home-search-company">
                        <div class="home-search-company__content">
                            <div class="row aligned-items-center">
                                <div class="col-lg-8 col-xs-12 no-padding">

                                    <h5 class="home-search-company__content__title">ORGANIZZI CORSI?</h5>
                                    <h2 class="home-search-company__content__description">AGGIUNGI <br> GRATUITAMENTE LA
                                        TUA SOCIETÁ SPORTIVA</h2>
                                </div>
                                <div class="col-lg-4 col-xs-12 no-padding">
                                    @if (\Illuminate\Support\Facades\Auth::user())
                                        <a href="{{route('companies.landing')}}" class="btn orange-gradient-button font-weight-bold">Aggiungi la tua societàsportiva</a>
                                    @else
                                        <a href="https://landing.orangogo.it/insertsocmain/" class="btn orange-gradient-button font-weight-bold">Aggiungi la tua societàsportiva</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('seo.cities')

            </b-container>
        </div>
    </homepage>
@endsection
@section('scripts')
    @if(isset($scroll_howto))
        <script>
            $('html, body').animate({
                scrollTop: $("#howworks").offset().top
            }, 500);
            $(this).focus();
        </script>
    @endif
@stop
