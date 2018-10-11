<template>
    <b-row class="results-course-card" :class="{'results-course-card--detail': isDetail}">
        <b-col lg="4" sm="12" class="results-course-card__image clickable no-padding">
            <a :href="hrefCourse(course)">
                <img :src="course.course_image">
            </a>
            <!--<div class="results-course-card__image__tags" v-if="isDetail">
                <div v-for="(tag, index) in tempTags" :key="index" :class="'tag-' + tag.color">
                    <i class="fas" :class="tag.icon"></i>
                    {{tag.text}}
                </div>
            </div>-->
        </b-col>
        <b-col lg="8" sm="12" class="results-course-card__info-container no-padding">
            <b-col lg="8" sm="12" class="results-course-card__left-info no-padding">
                <div class="results-course-card__body" :class="{'mr-auto': isDetail}">
                    <p class="results-course-card__body__address">
                        <i class="fa fa-map-marker-alt"></i>
                        {{course.address}}
                    </p>
                    <h1 class="results-course-card__body__name">
                        <a :href="hrefCourse(course)">
                            <b>{{course.seo_name_short}}</b>
                        </a>
                    </h1>
                    <div class="d-flex align-items-center mb-3" v-if="isDetail">
                <span class="results-course-card__body__age-range">
                    per ragazzi e adulti tra {{course.start_age}} - {{course.end_age}} anni
                </span>
                        <!--<div class="results-course-card__body__rating">
                            <i class="fa-star" :class="{fas: i <= 4, far: i > 4}" v-for="i in [1, 2, 3, 4, 5]" :key="i"></i>
                            <span class="ml-1">(4)</span>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-lg-9 no-padding">
                            <div class="results-course-card__body__lessons">
                <span v-for="day in days" class="results-course-card__body__lessons__day"
                      :class="{'results-course-card__body__lessons__day--has-lesson': day.lesson}">{{day.name}}</span>
                            </div>
                        </div>
                        <div class="col-lg-3 no-padding aligned-items-center">
                            <div class="results-course-card__body__short-age-range">
                                <p>
                                    {{course.start_age}} - {{course.end_age}} anni
                                </p>
                            </div>
                        </div>
                    </div>


                    <p v-if="course.distance_from_my_position" class="results-course-card__body__age-range">
                        <i class="fa fa-map-marker-alt"></i>
                        a {{course.distance_from_my_position | round }} km da te
                    </p>
                </div>
            </b-col>
            <b-col lg="4" sm="12" class="results-course-card__right-info no-padding">
                <div class="results-course-card__addition" v-if="isDetail">
                    <p class="results-course-card__addition__price" v-if="course.min_price">Corsi a partire da <strong>{{course.min_price}}
                        €</strong></p>
                    <p class="results-course-card__addition__free-trial" v-if="course.has_trial_lesson">Prova <strong>GRATUITA</strong>
                    </p>
                </div>
            </b-col>
        </b-col>
        <b-col lg="3" sm="12" class="results-course-card__actions" v-if="!isDetail">
            <a class="white-solid-button" :href="hrefCourse(course)">Prenota Ora</a>
        </b-col>
    </b-row>
</template>

<script>
    export default {
        name: 'HomeCourseCard',
        props: {
            course: {
                type: Object,
                required: true
            },
            isDetail: {
                type: Boolean,
                required: false
            }
        },
        data() {
            return {
                days: [
                    {name: 'L', long: 'Lunedì', dow: 0, lesson: false},
                    {name: 'M', long: 'Martedì', dow: 1, lesson: false},
                    {name: 'M', long: 'Mercoledì', dow: 2, lesson: false},
                    {name: 'G', long: 'Giovedì', dow: 3, lesson: false},
                    {name: 'V', long: 'Venerdì', dow: 4, lesson: false},
                    {name: 'S', long: 'Sabato', dow: 5, lesson: false},
                    {name: 'D', long: 'Domenica', dow: 6, lesson: false}
                ],
                tempTags: [
                    {icon: 'fa-map-marker-alt', text: 'A 10 minuti da te', color: 'orangogo'},
                    {icon: 'fa-tag', text: '-30% sconto', color: 'primary'}
                ]
            }
        },
        mounted() {
            this.aggiornaDays();
        },
        watch: {
            'course.days'() {
                this.aggiornaDays();
            }
        },
        methods: {
            aggiornaDays() {
                let self = this;

                this.days.forEach(function (day) {
                    let isPresent = false;

                    self.course.days.forEach(function (course_day) {
                        if (course_day.dow === day.dow) {
                            isPresent = true;
                        }
                    });

                    day.lesson = isPresent;
                });
            },
            hrefCourse(course) {
                return `/corsi/${course.slug}`;
            }
        }
    }
</script>
