<template>
    <b-modal ref="sportsmanModal" hide-footer hide-header no-close-on-backdrop>
        <div class="modal-user-login">
            <b-form @submit.prevent="confirmSportsman" data-vv-scope="sportsman-form">
                <b-form-group>
                    <b-form-select :disabled="!friends || friends.length < 1"
                                   v-model="selectedFriend"
                                   value-field="id"
                                   v-show="friends && friends.length >= 1"
                                   text-field="name"
                                   :options="friends"
                                   class="form-input"/>
                </b-form-group>
                <b-form-group>
                    <b-form-input type="text"
                                  v-model="sportsman.firstName"
                                  placeholder="Nome dello sportivo che parteciperà"
                                  class="form-input">
                    </b-form-input>
                </b-form-group>
                <b-form-input type="text"
                              v-model="sportsman.lastName"
                              placeholder="Cognome dello sportivo che parteciperà"
                              class="form-input">
                </b-form-input>
                <p class="font-weight-bold mb-1">Data di nascita</p>
                <div class="d-flex justify-content-between mb-2">
                    <div class="flex-fill mr-2">
                        <b-form-group>
                            <b-form-select v-model="sportsman.birthday.day"
                                           :options="days"
                                           class="form-input"/>
                        </b-form-group>
                    </div>
                    <div class="flex-fill mx-2">
                        <b-form-group>
                            <b-form-select v-model="sportsman.birthday.month"
                                           :options="months"
                                           class="form-input"/>
                        </b-form-group>
                    </div>
                    <div class="flex-fill ml-2">
                        <b-form-group>
                            <b-form-select v-model="sportsman.birthday.year"
                                           :options="years"
                                           class="form-input"/>
                        </b-form-group>
                    </div>
                </div>
                <b-form-group>
                    <b-form-input type="email"
                                  v-model="sportsman.email"
                                  placeholder="Email"
                                  class="form-input">
                    </b-form-input>
                </b-form-group>
                <b-form-group v-if="selectedFriend === undefined">
                    <b-form-checkbox v-model="saveAsFriend">Ricorda questi dati in futuro</b-form-checkbox>
                </b-form-group>
                <b-form-group>
                    <b-btn class="orange-gradient-button text-capitalize" variant="default"
                           block type="submit">Prenota la prova
                    </b-btn>
                </b-form-group>
            </b-form>
        </div>
    </b-modal>
</template>

<script>
  export default {
    name: "ReservationSportsman",
    props:{
      'reservationId': String
    },
    data () {
      return {
        friends: [],
        days: [
          {value: undefined, text: 'Giorno'}
        ],
        months: [
          {value: undefined, text: 'Mese'},
          {value: 0, text: 'Gennaio'},
          {value: 1, text: 'Febbraio'},
          {value: 2, text: 'Marzo'},
          {value: 3, text: 'Aprile'},
          {value: 4, text: 'Maggio'},
          {value: 5, text: 'Giugno'},
          {value: 6, text: 'Luglio'},
          {value: 7, text: 'Agosto'},
          {value: 8, text: 'Settembre'},
          {value: 9, text: 'Ottobre'},
          {value: 10, text: 'Novembre'},
          {value: 11, text: 'Dicembre'}
        ],
        years: [
          {value: undefined, text: 'Anno'}
        ],
        selectedFriend: undefined,
        sportsman: {
          firstName: null,
          lastName: null,
          birthday: {
            day: undefined,
            month: undefined,
            year: undefined
          },
          email: null
        },
        saveAsFriend: false
      }
    },
    created () {
      for (let i = 1; i <= 31; i++) {
        this.days.push({value: i, text: i});
      }
      for (let i = new Date().getFullYear(); i >= 1970; i--) {
        this.years.push({value: i, text: i});
      }
    },
    mounted () {
      this.getFriends();
      this.$refs.sportsmanModal.show();
    },
    watch: {
      selectedFriend () {
        if (this.selectedFriend !== undefined) {
          let friend = this.friends.find((friend) => {
            return friend.id == this.selectedFriend;
          });
          let birthday = new Date(friend.birthday);
          let day = birthday.getDate();
          let month = birthday.getMonth();
          let year = birthday.getFullYear();

          this.sportsman = {
            firstName: friend.first_name,
            lastName: friend.last_name,
            birthday: {
              day: day,
              month: this.months.find((singleMonth) => singleMonth.value == month).value,
              year: year,
            },
            email: friend.email
          };
        }
        else {
          this.sportsman = {
            firstName: null,
            lastName: null,
            birthday: {
              day: undefined,
              month: undefined,
              year: undefined
            },
            email: null
          };
          this.saveAsFriend = false;
        }
      },
    },
    methods: {
      getFriends () {
        axios({
          method: 'get',
          url: '/friends'
        }).then((response) => {
          if (response.data.friends.length > 0) {
            this.friends = [{'id': undefined, 'name': 'Scegli chi praticherà'}];
            response.data.friends.forEach((friend) => {
              friend.name = friend.first_name + ' ' + friend.last_name;
              this.friends.push(friend);
            });
          }
          else {
            //this.friends = [{'id': undefined, 'name': 'No saved sportsmen', disabled: true}];
          }
        }).catch((error) => {
          this.message = 'Error';
        });
      },
      confirmSportsman () {
        axios({
          method: 'put',
          url: '/reservations/' + this.reservationId + '/confirmations',
          data: {
            sportsman: this.sportsman,
            saveAsFriend: this.saveAsFriend
          }
        }).then((response) => {
          window.location.href = '/reservations/' + this.reservationId + '/confirm'
        }).catch((err) => {
          this.handleError(err.response);
        });
      },
    }
  }
</script>

<style scoped>

</style>