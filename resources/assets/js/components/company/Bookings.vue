<template>

</template>

<script>
    export default {
        name: "Bookings",
        props: ['reservationsprop'],
        data () {
            return {
                orderList: [
                    { id: 'more', name: 'Ordina per più recenti' },
                    { id: 'less', name: 'Ordina per meno recenti' }
                ],
                filterList: [
                    { id: 'swimming', name: 'Corso di Nuoto 15-20 anni' },
                    { id: 'lodging', name: 'Corso di CAnotaggio 16-24 anni' }
                ],
                selected: {},
                reservations: []
            }
        },
        mounted() {
            this.reservations = this.reservationsprop
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
            }
        }
    }
</script>

<style scoped>

</style>
