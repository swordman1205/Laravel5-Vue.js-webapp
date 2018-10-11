<script>
  export default {
    name: "Reservations",
    props: ['reservationsprop'],
    data () {
      return {
        showReservation: null,
        reservations: [],
        orderList: [
              { id: 'more', name: 'Ordina per più recenti' },
            { id: 'less', name: 'Ordina per meno recenti' }
        ],
        filterList: [
            { id: 'swimming', name: 'Corso di Nuoto 15-20 anni' },
            { id: 'lodging', name: 'Corso di CAnotaggio 16-24 anni' }
        ],
      }
    },
    mounted () {
        this.reservations = this.reservationsprop;
    },
    methods: {
        formatDate(date) {
            return moment(date).format('DD/MM/YYYY')
        },
        formatTime(date) {
            return moment(date).format('LT')
        },
        calculateAge(birthday) {
            var from = moment(birthday).fromNow();
            return from.match(/\d+/)[0];
        },
        confirm(){
            axios({
                method: 'put',
                url: '/reservations/' + this.showReservation.id + '/confirm'
            }).then((response) => {
                window.alerts.success('Reservation confirmed');

                this.reservations.map((reservation)=>{
                    if(reservation.id === this.showReservation.id){
                        reservation.confirmed_by_user = 1;
                    }
                });

                this.showReservation = null;
            }).catch((error) => {
                window.alerts.error('Could not confirm reservation');
            });
        }
    }
  }
</script>

<style scoped>

</style>