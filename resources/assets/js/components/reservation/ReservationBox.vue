<template>
    <div class="col-lg-6">
    <div class="card shadow-sm reservation-box">
        <div class="card-img-top">
            <template v-if="courseInfo.course_image">
                <img :src="courseInfo.course_image">
            </template>
            <template v-else>
                <div class="reservation-box__image--placeholder"></div>
            </template>
            <div class="reservation-box__overlay">
                <h4 class="reservation-box__overlay__name">{{courseInfo.name}}</h4>
                <h6 class="reservation-box__overlay__school">{{courseInfo.company.name}}</h6>
            </div>
        </div>
        <div class="card-body">
            <p class="card-title">Prenota la tua lezione di prova</p>
            <hr>
            <div class="card-text reservation-box__detail">
                <ul class="list-unstyled">
                    <li>
                        <ReservationAddress
                                :address="courseInfo.address">
                        </ReservationAddress>
                    </li>
                    <li>
                        <div class="reservation-box__price">
                            <i class="fa fa-dollar-sign circle"></i>
                            {{(!courseInfo.lesson_price || courseInfo.lesson_price === '0')? 'GRATIS' :
                            courseInfo.lesson_price}}
                        </div>
                    </li>
                </ul>
                <a href="#" class="reservation-box__btn--share">
                    <i class="fa fa-share-alt"></i>
                </a>
            </div>
            <div class="card-text reservation-box__days" v-if="isDays">
                <hr>
                <h5 class="reservation-box__days__intro">Scegli il giorno della tua lezione di prova:</h5>
                <ul class="list-unstyled">
                    <template v-if="courseInfo.lesson_book_datetime">
                        <li v-for="(lesson, index) in courseInfo.lesson_book_datetime" :key="index">
                            <ReservationLessonCard
                                    :lesson="lesson"
                                    :active="lesson === selectedLesson"
                                    :select-lesson="toggleLesson">
                            </ReservationLessonCard>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            No lessons available for this course
                        </li>
                    </template>
                </ul>
                <a class="reservation-box__days__guide" href="javascript:void(0)" @click="chooseDate" v-if="!courseInfo.lesson_date_time">SCEGLI DATA SPECIFICA</a>
            </div>
        </div>
        <ul class="list-group list-group-flush reservation-box__actions">
            <li class="list-group-item text-uppercase reservation-box__actions__book orange-gradient-button"
                :class="{'reservation-box__actions__book--disabled': isDays && !selectedLesson}" @click="toggleDays">
                Prenota
            </li>
            <li class="list-group-item reservation-box__actions__test-lesson">
                <i class="fa fa-gift"></i>
                Regala la lezione di prova ad un amico!
            </li>
        </ul>
        <b-modal
                ref="reservationModalRef"
                modal-class="reservation__modal"
                hide-header
                hide-footer>
            <ReservationModal
                    :course-info="courseInfo"
                    :selected-lesson="selectedLesson"
                    :step="modalStep">
                <a class="reservation__modal__btn--close" href="javascript:void(0)" @click="hideModal">
                    <i class="fa fa-times"></i>
                </a>
            </ReservationModal>
        </b-modal>

        <div v-for="lessonPackage in lessonPackages">
            <button @click="addToCart(lessonPackage, 'App\\LessonPackage')">Aggiungi al carrello questo pacchetto di {{lessonPackage.n_lessons}} lezioni</button>
        </div>
        <div v-for="courseSubscription in subscriptions">
          <button @click="addToCart(courseSubscription, 'App\\CourseSubscription')">Aggiungi al carrello questo abbonamento di {{courseSubscription.subscription.name}}</button>
        </div>
    </div>
    </div>
</template>

<script>
    const moment = require('moment');

    import ReservationAddress from './ReservationAddress'
    import ReservationModal from './ReservationModal'
    import ReservationLessonCard from './ReservationLessonCard'

    export default {
        name: "ReservationBox",
        props: {
            course: {},
            lessonpackagesprop: null,
            subscriptionsprop: null
        },
        components: {
            ReservationAddress,
            ReservationModal,
            ReservationLessonCard
        },
        data() {
            return {
                isDays: false,
                selectedLesson: null,
                weekDays: [
                    {value: 0, long: 'Domenica', short: 'DO'},
                    {value: 1, long: 'Lunedì', short: 'LUN'},
                    {value: 2, long: 'Martedì', short: 'MAR'},
                    {value: 3, long: 'Mercoledì', short: 'MER'},
                    {value: 4, long: 'Giovedì', short: 'GIO'},
                    {value: 5, long: 'Venerdì', short: 'VEN'},
                    {value: 6, long: 'Sabato', short: 'SAB'}
                ],
                months: [
                    {value: 0, name: 'Gennaio'},
                    {value: 1, name: 'Febbraio'},
                    {value: 2, name: 'Marzo'},
                    {value: 3, name: 'Aprile'},
                    {value: 4, name: 'Maggio'},
                    {value: 5, name: 'Giugno'},
                    {value: 6, name: 'Luglio'},
                    {value: 7, name: 'Agosto'},
                    {value: 8, name: 'Settembre'},
                    {value: 9, name: 'Ottobre'},
                    {value: 10, name: 'Novembre'},
                    {value: 11, name: 'Dicembre'}
                ],
                lessonPackages: [],
                subscriptions: [],
                modalStep: 1
            }
        },
        mounted() {
            this.lessonPackages = this.lessonpackagesprop;
            this.subscriptions = this.subscriptionsprop;
        },
        methods: {
            addToCart(element, type) {
                axios({
                    method: 'post',
                    url: 'addToCart/',
                    data: {
                        buyable: element,
                        type: type
                    }
                }).then((response) => {
                    this.federations = response.data.federations;
                }).catch((error) => {
                    this.message = 'Error';
                });
            },
            toggleDays () {
                if (!this.isDays) {
                    this.isDays = !this.isDays;
                } else if (this.isDays && this.selectedLesson) {
                    this.modalStep = 1;
                    this.$refs.reservationModalRef.show();
                }
            },
            toggleLesson(lesson) {
                this.selectedLesson = this.selectedLesson === lesson ? null : lesson;
            },
            hideModal() {
                this.$refs.reservationModalRef.hide();
                this.modalStep = 1;
            },
            chooseDate () {
                console.log("pino");
                this.modalStep = 0;
                this.$refs.reservationModalRef.show();
            }
        },
        computed: {
            courseInfo() {
                const course = this.course;
                const dayOfToday = moment().day();
                if (course.lesson_date_time && moment().diff(course.lesson_date_time) <= 0) {
                    const bookedMoment = moment(course.lesson_date_time);
                    course.lesson_book_datetime = [{
                        course_id : course.id,
                        month: this.months[bookedMoment.month()].name,
                        date: bookedMoment.date(),
                        short_day: this.weekDays[bookedMoment.day()].short,
                        time: bookedMoment.format('HH:mm'),
                        datetime: bookedMoment.format('YYYY-MM-DD HH:mm')
                    }];
                } else if (!course.lesson_date_time) {
                    course.lesson_book_datetime = [];
                    for (let date of course.days) {
                        const dayObj = this.weekDays.find(day => day.long.toLowerCase() === date.day.toLowerCase());
                        if (!dayObj) continue;
                        const diff = dayObj.value - dayOfToday >= 0 ? (dayObj.value - dayOfToday) : (dayObj.value - dayOfToday + 7);
                        const currentMoment = moment().add(diff, 'd');
                        let start_time = date['start_time'].split(':').slice(0, 2).join(':');

                        course.lesson_book_datetime.push({
                            course_id : course.id,
                            start_time: start_time,
                            end_time: date['end_time'].split(':').slice(0, 2).join(':'),
                            date: currentMoment.date(),
                            month: this.months[currentMoment.month()].name,
                            short_day: dayObj.short,
                            datetime: moment(currentMoment.format('YYYY-MM-DD') + ' ' + start_time).format('YYYY-MM-DD HH:mm'),
                        });
                    }
                }
                return course;
            }
        }
    }
</script>
