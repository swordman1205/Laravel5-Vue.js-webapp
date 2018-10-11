<template>
    <div class="p-5">
        <template v-if="contentStep === 0">
            <div class="reservation-modal">
                <datepicker :inline="true"
                            :disabled-dates="disabledDates"
                            v-model="specificDate">
                </datepicker>
            </div>
            <button class="btn orange-gradient-button reservation__modal__confirm col-12 m-t-20" @click="confirmDate">Conferma</button>
        </template>
        <template v-else-if="contentStep === 1">
            <h4 class="reservation__modal__name">{{courseInfo.name}}</h4>
            <p class="reservation__modal__company">{{courseInfo.company.name}}</p>
            <ReservationAddress
                    :address="courseInfo.address">
            </ReservationAddress>
            <p class="reservation__modal__price"><span>COSTO LEZIONE</span> {{(!courseInfo.lesson_price ||
                courseInfo.lesson_price === '0')? 'Gratis (0€)' : courseInfo.lesson_price + '€'}}</p>
            <ReservationLessonDropdown
                    :lessons="lessons"
                    :selected-lesson="selected || selectedLesson"
                    :select-lesson="selectLesson">
            </ReservationLessonDropdown>
            <button class="btn orange-gradient-button reservation__modal__confirm col-12 m-t-20" @click="confirm">Conferma</button>
        </template>
        <template v-else>
            <!-- @todo: confirm modal -->
        </template>
        <slot></slot>
    </div>
</template>

<script>
    import ReservationLessonDropdown from './ReservationLessonDropdown';
    import ReservationAddress from './ReservationAddress';
    import datepicker from 'vuejs-datepicker'
    import {mapGetters} from 'vuex';

    const moment = require('moment');

    export default {
        name: "ReservationModal",
        data() {
            return {
                selected: null,
                specificDate: null,
                contentStep: 1
            }
        },
        props: {
            courseInfo: Object,
            selectedLesson: Object,
            step: {
                type: Number,
                default: 1
            }
        },
        components: {
            ReservationLessonDropdown,
            ReservationAddress,
            datepicker
        },
        mounted() {
            if (this.storedReservation) {
                this.selected = this.storedReservation;
                this.$store.dispatch('setReservationState', null);
                this.confirm();
            }
        },
        watch: {
            selectedLesson() {
                this.selected = this.selectedLesson;
            },
            step () {
                this.contentStep = this.step;
            }
        },
        computed: {
            ...mapGetters({
                storedReservation: 'storedReservation'
            }),
            lessons() {
                return this.courseInfo.lesson_book_datetime;
            },
            disabledDates() {
                const disabledDays = []
                for (const day of this.commonWeekDays) {
                    const availableDays = this.courseInfo.days.map(lesson => lesson.day)
                    if (availableDays.indexOf(day.long) === -1) {
                        disabledDays.push(day.value);
                    }
                }
                return {
                    to: new Date(),
                    days: disabledDays
                }
            }
        },
        methods: {
            selectLesson(lesson) {
                this.selected = lesson;
                this.selected.course_day_id = lesson.id;
            },
            reservationFromLesson(lesson) {
                return course;
            },
            createReservation() {
                axios({
                    method: 'post',
                    url: '/reservations',
                    data: {
                        reservation: this.selected,
                    }
                }).then((response) => {
                  this.$store.dispatch('setReservationState', null);
                  window.location.href = '/reservations/' + response.data.reservation.id + '/confirmations';
                }).catch((error) => {
                    window.alerts.error('Could not create reservation');
                });
            },
            confirm() {
                if (!window.currentUserId) {
                    this.$store.dispatch('setReservationState', this.selected);
                    this.$store.dispatch('setLoginCallback', this.createReservation);
                    this.$store.dispatch('setRedirect', null);
                    this.$store.dispatch('setSocialLoginRedirect', '/corsi/' + this.courseInfo.slug);
                    this.$store.dispatch('onShowLogin');
                }
                else {
                    this.createReservation().then((reservation) => {
                        window.location.href = '/reservations/' + reservation.id + '/confirmations';
                    });
                }
            },
            confirmDate() {
                if (!this.specificDate) return
                const selectedDate = moment(this.specificDate)
                const selectedDay = this.commonWeekDays[selectedDate.day()].long
                const index = this.courseInfo.days.findIndex(lesson => lesson.day === selectedDay)
                if (index > -1) {
                    this.selected = {
                        ...this.courseInfo.lesson_book_datetime[index],
                        date: selectedDate.date(),
                        month: this.commonMonths[selectedDate.month()].name,
                        specific: true
                    }
                    this.contentStep = 1
                }
            }
        }
    }
</script>
