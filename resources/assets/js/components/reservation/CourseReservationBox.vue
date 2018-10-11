<template>
    <div class="col-xs-12">
        <div class="card-img-top ">
            <template v-if="courseInfo.course_image">
                <div class="col-12 m-0 p-0 img-carousel">
                    <div class="img-carousel__container">
                        <div class="img-carousel__wrap"><img :src="courseInfo.course_image"
                                                             class="small-rounded border-shadow-grey col-12 m-0 p-0">
                        </div>
                        <div v-for="image in galleryImages" :style="setGalleryImageWidth()" class="img-carousel__wrap">
                            <img :src="image.file_path" class="small-rounded border-shadow-grey col-12 m-0 p-0"></div>
                    </div>
                    <button v-if="galleryImages.length>0" class="btn btn-prev-image aligned-items-center"
                            @click="prevImg()"><i class="fa fa-caret-left"></i></button>
                    <button v-if="galleryImages.length>0" class="btn btn-next-image aligned-items-center"
                            @click="nextImg()"><i class="fa fa-caret-right"></i></button>
                </div>
            </template>
            <template v-else>
                <div class="reservation-box__image--placeholder"></div>
            </template>
            <div class="large-rounded border-shadow-grey course-box-overlap">
                <div class="p-25">
                    <div class="text-center">
                        <i class="text-orangogo2-color fa fa-map-marker-alt"></i>&nbsp;&nbsp;{{ courseInfo.address }}
                    </div>

                    <div class="divider-gray-200"></div>

                    <!--<p style="font-size:1.3em"><i class="text-orangogo2-color fa fa-clock"></i>&nbsp;&nbsp;{{
                        hoursInWeek() }} {{ hoursInWeek() > 1 ? "Ore" : "Ora" }} a settimana</p>-->
                    <div class="row text-left" style="font-size:0.8em">
                        <div v-for="lesson in courseInfo.days" class="col-4 col-xs-12 row">
                            <span><strong>{{ lesson.short_day}}</strong> {{ lesson.short_start_time }}</span>
                        </div>
                    </div>

                    <div class="divider-gray-200"></div>
                    <p style="font-size:1.3em" class="text-center">PRENOTA LA TUA PROVA</p>
                    <template v-if="courseInfo.lesson_book_datetime">
                        <div class="reservation-box__lessoncards row m-t-10">
                            <div v-if="lessonInRow(index,1)" v-for="(lesson, index) in courseInfo.lesson_book_datetime"
                                 :key="index" :class="reservationBoxLessoncardClass(lesson)"
                                 @click="toggleLesson(lesson)">
                                <div class="text-center">
                                    {{lesson.short_day}} {{lesson.date}} {{lesson.month_short}}<br>
                                    {{ lesson.start_time ? lesson.start_time + ' - ' + lesson.end_time : lesson.time }}
                                </div>
                            </div>
                        </div>
                        <div class="reservation-box__lessoncards row m-t-10">
                            <div v-if="lessonInRow(index,2)" v-for="(lesson, index) in courseInfo.lesson_book_datetime"
                                 :key="index" :class="reservationBoxLessoncardClass(lesson)"
                                 @click="toggleLesson(lesson)">
                                <div class="text-center">
                                    {{lesson.short_day}} {{lesson.date}} {{lesson.month_short}}<br>
                                    {{ lesson.start_time ? lesson.start_time + ' - ' + lesson.end_time : lesson.time }}
                                </div>
                            </div>
                        </div>
                        <div class="reservation-box__lessoncards row m-t-10">
                            <div v-if="lessonInRow(index,3)" v-for="(lesson, index) in courseInfo.lesson_book_datetime"
                                 :key="index" :class="reservationBoxLessoncardClass(lesson)"
                                 @click="toggleLesson(lesson)">
                                <div class="text-center">
                                    {{lesson.short_day}} {{lesson.date}} {{lesson.month_short}}<br>
                                    {{ lesson.start_time ? lesson.start_time + ' - ' + lesson.end_time : lesson.time }}
                                </div>
                            </div>
                        </div>
                        <div class="reservation-box__actions">
                            <div class="text-center text-uppercase reservation-box__actions__book orange-gradient-button"
                                 :class="{'reservation-box__actions__book--disabled': !selectedLesson}"
                                 @click="showReservationLesson">
                                <div class="reservation-box__actions__text p-15">Prenota prova</div>
                                <span class="reservation-box__actions__price">
                                <div class="p-15">
                                {{(!courseInfo.lesson_price || courseInfo.lesson_price === '0')? 'GRATIS' :
                                    courseInfo.lesson_price + ' &euro;'}}
                                </div>
                            </span>
                            </div>
                        </div>
                        <a class="reservation-box__days__guide" href="javascript:void(0)" @click="chooseDate"
                           v-if="!courseInfo.lesson_date_time">SCEGLI DATA SPECIFICA</a>
                    </template>
                    <template v-else>
                        <li>
                            nessuna lezione disponibile
                        </li>
                    </template>


                    <div v-if="course.has_subscriptions || course.has_lesson_packages">
                        <div class="divider-gray-200"></div>
                        <p style="font-size:1.3em" class="text-center">ACQUISTA IL CORSO</p>

                        <div v-if="course.has_subscriptions" v-for="(subscription, index) in subscriptions"
                             class="p-0 col-12 m-t-10">
                            <div class="reservation-box__lessoncard reservation-box__lessoncard--active">
                                <span v-if="typeof subscription.cart != 'undefined'"
                                      class="dismiss-image-btn dismiss-image-btn__big">{{ subscription.cart > 1 ? subscription.cart : '' }}<i
                                        class="fa fa-shopping-cart"></i></span>
                                <div class="text-center text-uppercase reservation-box__actions__book p-0" :key="index"
                                     @click="addToCart(subscription,'App\\CourseSubscription')">
                                    <div class="reservation-box__actions__text p-10">Abbonamento {{
                                        subscription.subscription.name}}
                                    </div>
                                    <span class="reservation-box__actions__price orange-gradient-button orange-gradient-button__nobase">
                                    <div class="p-10 p-l-15">{{ subscription.price + ' &euro;'}}</div>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="subscriptions.length> 0 && lessonPackages.length> 0" class="m-t-30"></div>

                        <div v-if="course.has_lesson_packages" v-for="(lessonPackage, index) in this.lessonPackages"
                             class="p-0 col-12 m-t-10">
                            <div class="reservation-box__lessoncard reservation-box__lessoncard--active">
                                <span v-if="typeof lessonPackage.cart != 'undefined'"
                                      class="dismiss-image-btn dismiss-image-btn__big">{{ lessonPackage.cart > 1 ? lessonPackage.cart : '' }}<i
                                        class="fa fa-shopping-cart"></i></span>
                                <div class="text-center text-uppercase reservation-box__actions__book p-0"
                                     @click="addToCart(lessonPackage,'App\\LessonPackage')">
                                    <div class="reservation-box__actions__text p-10">Pacchetto {{
                                        lessonPackage.n_lessons }} Lezioni
                                    </div>
                                    <span class="reservation-box__actions__price orange-gradient-button orange-gradient-button__nobase">
                                    <div class="p-10 p-l-15">{{ lessonPackage.price + ' &euro;'}}</div>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-tiny p-l-10 p-t-10 text-center">Quota associativa {{
                            !course.membership_fee_included && course.membership_fee ?
                            course.membership_fee.toFixed(2).replace('.',',') + ' &euro;' : 'inclusa'}}
                        </div>
                    </div>
                </div>
                <span class="p-l-30 hidden">carrello: {{ cart_lenth }}</span>
            </div>
        </div>
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
            subscriptionsprop: null,
            galleryimagesprop: null
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
                shortDays: {
                    'Domenica': 'DOM',
                    'Lunedì': 'LUN',
                    'Martedì': 'MAR',
                    'Mercoledì': 'MER',
                    'Giovedì': 'GIO',
                    'Venerdì': 'VEN',
                    'Sabato': 'SAB',
                },
                weekDays: [
                    {value: 0, long: 'Domenica', short: 'DOM'},
                    {value: 1, long: 'Lunedì', short: 'LUN'},
                    {value: 2, long: 'Martedì', short: 'MAR'},
                    {value: 3, long: 'Mercoledì', short: 'MER'},
                    {value: 4, long: 'Giovedì', short: 'GIO'},
                    {value: 5, long: 'Venerdì', short: 'VEN'},
                    {value: 6, long: 'Sabato', short: 'SAB'}
                ],
                months: [
                    {value: 0, name: 'Gennaio', sshort: 'Gen'},
                    {value: 1, name: 'Febbraio', short: 'Feb'},
                    {value: 2, name: 'Marzo', short: 'Mar'},
                    {value: 3, name: 'Aprile', short: 'Apr'},
                    {value: 4, name: 'Maggio', short: 'Mag'},
                    {value: 5, name: 'Giugno', short: 'Giu'},
                    {value: 6, name: 'Luglio', short: 'Lug'},
                    {value: 7, name: 'Agosto', short: 'Ago'},
                    {value: 8, name: 'Settembre', short: 'Set'},
                    {value: 9, name: 'Ottobre', short: 'Ott'},
                    {value: 10, name: 'Novembre', short: 'Nov'},
                    {value: 11, name: 'Dicembre', short: 'Dic'}
                ],
                galleryImages: [],
                lessonPackages: [],
                subscriptions: [],
                subscriptionServices: [[], []],
                lessonPackageServices: [[], []],
                cart_lenth: 0,
                modalStep: 1
            }
        },
        mounted() {
            this.lessonPackages = this.lessonpackagesprop;
            this.subscriptions = this.subscriptionsprop;
            this.galleryImages = this.galleryimagesprop;
            $(".img-carousel__container").width($(".img-carousel").width() * 15 + "px");
            $(".img-carousel__wrap").width($(".img-carousel").width() + "px");
            $(".img-carousel__wrap").show();
        },
        methods: {
            nextImg() {
                $(".img-carousel__container").animate({
                    marginLeft: "-=" + $(".img-carousel").width()
                }, 1000, function () {
                    $(".img-carousel__container").append($(".img-carousel__wrap").first().remove());
                    $(".img-carousel__container").css("marginLeft", "0px");
                });
            },
            prevImg() {
                $(".img-carousel__container").prepend($(".img-carousel__wrap").last().remove());
                $(".img-carousel__container").css("marginLeft", "-" + $(".img-carousel").width() + "px");
                $(".img-carousel__container").animate({
                    marginLeft: "0px"
                }, 1000);
            },
            addToCart(element, type) {
                var self = this;

                if (!window.currentUserId) {
                    this.$store.dispatch('setReservationState', this.selected);
                    this.$store.dispatch('setRedirect', window.location);
                    this.$store.dispatch('setSocialLoginRedirect', '/corsi/' + this.courseInfo.slug);
                    this.$store.dispatch('onShowLogin');
                    return;
                }

                this.cart_lenth++;
                element.cart = (typeof element.cart == "undefined" ? 1 : element.cart + 1);
                axios({
                    method: 'post',
                    url: 'addToCart',
                    data: {
                        buyable: element,
                        type: type
                    }
                }).then((response) => {
                    window.alerts.success('Elemento aggiunto al carrello');
                    self.federations = response.data.federations;
                }).catch((error) => {
                    this.message = 'Error';
                });
            },
            toggleLesson(lesson) {
                this.selectedLesson = this.selectedLesson == lesson ? null : lesson;
            },
            hideModal() {
                this.modalStep = 1;
                this.$refs.reservationModalRef.hide();
            },
            chooseDate() {
                this.modalStep = 0;
                this.$refs.reservationModalRef.show();
            },
            hoursInWeek() {
                var hours = 0;
                for (let date of this.course.days) {
                    let start = date.start_time.split(":");
                    let t1 = start[2] * 1 + start[1] * 60 + start[0] * 60 * 60;
                    let end = date.end_time.split(":");
                    let t2 = end[2] * 1 + end[1] * 60 + end[0] * 60 * 60;

                    hours += Math.round((t2 - t1) / 60 / 60);

                }
                return hours;
            },
            reservationBoxLessoncardClass(lesson) {
                var classe = "p-0 reservation-box__lessoncard";
                if (lesson == this.selectedLesson) classe += " reservation-box__lessoncard--active";
                if (this.courseInfo.lesson_book_datetime.length == 1) classe += ' col-12';
                else if (this.courseInfo.lesson_book_datetime.length % 3 == 0 || this.courseInfo.lesson_book_datetime.length > 4) classe += ' col-4 col-xs-12';
                else classe += ' col-6 col-xs-12';
                return classe;
            },
            lessonInRow(index, row) {
                if (this.courseInfo.lesson_book_datetime.length % 3 == 0 || this.courseInfo.lesson_book_datetime.length > 4)
                    return index < row * 3 && index >= (row - 1) * 3;
                return index < row * 2 && index >= (row - 1) * 2;
            },
            setGalleryImageWidth() {
                return "width: " + $(".img-carousel").width() + "px";
            },
            showReservationLesson() {
                if (this.selectedLesson)
                    this.$refs.reservationModalRef.show();
            },
        },
        computed: {
            courseInfo() {
                var course = this.course;
                for (var i in course.days) {
                    course.days[i].short_day = this.shortDays[course.days[i].day];
                    course.days[i].short_start_time = course.days[i].start_time.split(':').slice(0, 2).join(':');
                    course.days[i].short_end_time = course.days[i].end_time.split(':').slice(0, 2).join(':');
                }
                const dayOfToday = moment().day();
                if (course.lesson_date_time && moment().diff(course.lesson_date_time) <= 0) {
                    const bookedMoment = moment(course.lesson_date_time);
                    course.lesson_book_datetime = [{
                        course_id: course.id,
                        month: this.months[bookedMoment.month()].name,
                        month_short: this.months[bookedMoment.month()].short,
                        date: bookedMoment.date(),
                        long_day: this.weekDays[bookedMoment.day()].long,
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
                            course_id: course.id,
                            start_time: start_time,
                            end_time: date['end_time'].split(':').slice(0, 2).join(':'),
                            date: currentMoment.date(),
                            month: this.months[currentMoment.month()].name,
                            month_short: this.months[currentMoment.month()].short,
                            short_day: dayObj.short,
                            long_day: dayObj.long,
                            datetime: moment(currentMoment.format('YYYY-MM-DD') + ' ' + start_time).format('YYYY-MM-DD HH:mm'),
                        });
                    }
                }
                return course;
            }
        }
    }
</script>
