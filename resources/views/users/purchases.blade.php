<b-row class="profile__reservation">
    <b-col cols="12">
        <a href="{{route('home')}}" class="profile__reservation__btn--back">
            <i class="fa fa-angle-left"></i> TORNA ALLA HOME
        </a>
    </b-col>
    <b-col cols="12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="profile__reservation__title">Acquisti ({{count($purchases)}})</h2>
            <b-button class="profile__reservation__btn--download">SCARICA PDF</b-button>
        </div>
    </b-col>
    <b-col cols="12">
        <ul class="profile__reservation__user-list">
            <li class="profile__reservation__user-list__row profile__reservation__user-list__row--header">
                <div>IN DATA</div>
                <div class="text-center">CORSO</div>
                <div class="text-center">Tipologia</div>
                <div class="text-center">Codice acquisto</div>
            </li>
            @foreach($purchases as $purchase)
                <li class="profile__reservation__user-list__row profile__reservation__user-list__row--body">
                    <div>{{\Illuminate\Support\Carbon::parse($purchase->created_at)->format('d/M/Y')}}</div>
                    <div class="profile__reservation__user-list__row__course">
                        <p class="profile__reservation__user-list__row__course__name">{{$purchase->buyable->course->name}}</p>
                        <p class="profile__reservation__user-list__row__course__address"><i class="fa fa-map-marker-alt"></i>{{$purchase->buyable->course->address}}</p>
                    </div>
                    @if ($purchase->buyable instanceof \App\LessonPackage)
                        <div>Pacchetto di {{$purchase->buyable->n_lessons}} lezioni</div>
                    @elseif ($purchase->buyable instanceof \App\CourseSubscription)
                        <div>Pacchetto di {{$purchase->buyable->subscription->name}}</div>
                    @endif
                    <div>{{$purchase->purchase->purchase_code}}</div>
                </li>
            @endforeach
        </ul>
    </b-col>
</b-row>
