<b-row class="dashboard__booking">
  <b-col cols="12">
    <a href="#" class="dashboard__booking__btn--back">
      <i class="fa fa-angle-left"></i> TORNA ALLA DASHBOARD
    </a>
  </b-col>
  <b-col cols="12">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="dashboard__booking__title">Prenotazioni (@{{reservations.length}})</h2>
      <!--<b-button class="dashboard__booking__btn--download">SCARICA PDF</b-button>-->
    </div>
  </b-col>
  <!--<b-col cols="12">
    <div class="d-flex align-items-center">
      <custom-selector :selected="selected.order"
                       :options="orderList"
                       icon="angle"
                       inner-class="dashboard__booking__select-order"
                       placeholder="Ordina per:">
      </custom-selector>
      <custom-selector :selected="selected.filter"
                       :options="filterList"
                       icon="angle"
                       inner-class="dashboard__booking__select-filter"
                       placeholder="Filtra per COrso">
      </custom-selector>
      <b-button variant="outline-primary" class="dashboard__booking__btn--calendar">
        <i class="far fa-calendar-alt"></i>
      </b-button>
    </div>
  </b-col>-->
  <b-col cols="12">
    <ul class="dashboard__booking__user-list">
      <li class="dashboard__booking__user-list__row dashboard__booking__user-list__row--header">
        <div></div>
        <div>Nome & cognome</div>
        <div>Età</div>
        <div>IN DATA</div>
        <div>Corso</div>
        <div>Data lezione</div>
      </li>
      <li v-for="(reservation, index) in reservations" :key="index" class="dashboard__booking__user-list__row dashboard__booking__user-list__row--body" v-if="reservation.confirmed_by_user">
        <div></div>
        <div>@{{reservation.first_name + ' ' + reservation.last_name}}</div>
        <div>@{{calculateAge(reservation.birthday)}}</div>
        <div>@{{formatDate(reservation.created_at)}}</div>
        <div class="dashboard__booking__user-list__row__course">
          <p class="dashboard__booking__user-list__row__course__name">@{{reservation.course.name}}</p>
          <p class="dashboard__booking__user-list__row__course__address"><i class="fa fa-map-marker-alt"></i>@{{reservation.course.address}}</p>
        </div>
        <div>@{{formatDate(reservation.datetime)}}<br>ore @{{formatTime(reservation.datetime)}}</div>
      </li>
    </ul>
  </b-col>
</b-row>
