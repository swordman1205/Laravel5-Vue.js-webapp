<template>
    <div class="home-course-card" :class="{'home-course-card--detail': isDetail, 'home-course-card--in-map': insideMap}">

        <div class="home-course-card__image clickable">
            <a :href="hrefCourse(course)">
                <img :src="course.course_image">
            </a>
            <div class="home-course-card__tags" v-if="isDetail || insideMap">
                <div v-for="(tag, index) in tempTags" :key="index" :class="'tag-' + tag.color">
                    <i class="fas" :class="tag.icon"></i>
                    {{tag.text}}
                </div>
            </div>
        </div>
        <div class="home-course-card__body" :class="{'mr-auto': isDetail}">
            <p class="home-course-card__body__address">
                <i class="fa fa-map-marker-alt"></i>
                {{course.address}}
            </p>
            <div class="home-course-card__body__name" :class="{'d-flex justify-content-between': insideMap}">
                {{course.name}}
                <!--<div class="home-course-card__body__rating" v-if="insideMap">
                    <i class="fa-star" :class="{fas: i <= 4, far: i > 4}" v-for="i in [1, 2, 3, 4, 5]" :key="i"></i>
                    <span class="ml-1">(4)</span>
                </div>-->
            </div>
            <div class="d-flex align-items-center mb-3" v-if="isDetail || insideMap">
                <span class="home-course-card__body__age-range">
                    per ragazzi e adulti tra {{course.start_age}} - {{course.end_age}} anni
                </span>
                <div class="home-course-card__body__rating" v-if="isDetail">
                    <i class="fa-star" :class="{fas: i <= 4, far: i > 4}" v-for="i in [1, 2, 3, 4, 5]" :key="i"></i>
                    <span class="ml-1">(4)</span>
                </div>
            </div>
            <div class="home-course-card__body__lessons" :class="{'mb-0': insideMap}">
                <span v-for="day in days" class="home-course-card__body__lessons__day"
                      :class="{'home-course-card__body__lessons__day--has-lesson': day.lesson}">{{day.name}}</span>
            </div>
            <p class="home-course-card__body__age-range" v-if="!isDetail && !insideMap">
                <i class="fa fa-user"></i>
                Da {{course.start_age}} a {{course.end_age}} anni
            </p>
            <p v-if="course.distance_from_my_position" class="home-course-card__body__age-range">
                <i class="fa fa-map-marker-alt"></i>
                a {{course.distance_from_my_position | round }} km da te
            </p>
        </div>
        <div class="home-course-card__addition text-right" v-if="isDetail">
            <p class="home-course-card__addition__price" v-if="course.min_price">Corsi a partire da <strong>{{course.min_price }} €</strong></p>
            <p class="home-course-card__addition__free-trial" v-if="course.has_trial_lesson">Prova <strong>GRATUITA</strong></p>
            <p class="home-course-card__addition__places-available">12 POSTI DISPONIBILI</p>
        </div>
        <div class="home-course-card__actions" v-if="!isDetail && !insideMap">
            <b-button class="white-solid-button" variant="outline-secondary" :href="hrefCourse(course)">Prenota Ora</b-button>
        </div>
    </div>
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
            },
            insideMap: {
                type: Boolean,
                required: false
            }
        },
        data() {
            return {
                days: [
                    {name: 'L', long: 'Lunedì'},
                    {name: 'M', long: 'Martedì'},
                    {name: 'M', long: 'Mercoledì'},
                    {name: 'G', long: 'Giovedì'},
                    {name: 'V', long: 'Venerdì'},
                    {name: 'S', long: 'Sabato'},
                    {name: 'D', long: 'Domenica'}
                ],
                tempTags: [
                    { icon: 'fa-map-marker-alt', text: 'A 10 minuti da te', color: 'orangogo' },
                    { icon: 'fa-tag', text: '-30% sconto', color: 'primary' }
                ]
            }
        },
        created() {
            if (!this.course.days) this.course.days = []
            for (const day of this.days) {
                const lesson = this.course.days.find(les => les.day.toLowerCase() === day.long.toLowerCase());
                if (lesson) {
                    day.lesson = true;
                }
            }
        },
        methods: {
            loadFilteredCourses(courses) {
                this.course = courses
            },
            hrefCourse(course) {
                return `/corsi/${course.slug}`;
            }
        }
    }
</script>
