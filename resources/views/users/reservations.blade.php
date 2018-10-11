<b-row class="profile__reservation">
    <b-col cols="12">
        <a href="{{route('home')}}" class="profile__reservation__btn--back">
            <i class="fa fa-angle-left"></i> TORNA ALLA HOME
        </a>
    </b-col>
    <b-col cols="12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="profile__reservation__title">Prenotazioni (@{{reservations.length}})</h2>
            <b-button class="profile__reservation__btn--download">SCARICA PDF</b-button>
        </div>
    </b-col>
    <b-col cols="12">
        <ul class="profile__reservation__user-list">
            <li class="profile__reservation__user-list__row profile__reservation__user-list__row--header">
                <div></div>
                <div>IN DATA</div>
                <div>Corso</div>
                <div>Data lezione</div>
            </li>
            <li v-for="(reservation, index) in reservations" :key="index" class="profile__reservation__user-list__row profile__reservation__user-list__row--body">
                <div>@{{index + 1}}</div>
                <div>@{{formatDate(reservation.created_at)}}</div>
                <div class="profile__reservation__user-list__row__course">
                    <p class="profile__reservation__user-list__row__course__name">@{{reservation.course.name}}</p>
                    <p class="profile__reservation__user-list__row__course__address"><i class="fa fa-map-marker-alt"></i>@{{reservation.course.address}}</p>
                </div>
                <div>@{{formatDate(reservation.datetime)}}<br>ore @{{formatTime(reservation.datetime)}}</div>
            </li>
        </ul>
    </b-col>
</b-row>
