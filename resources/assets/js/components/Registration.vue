<script>
    import axios from 'axios'
    import stepOne from './course/StepOne.vue';
    import stepTwo from './course/stepTwo.vue';
    import stepThree from './course/stepThree.vue';
    import stepFour from './course/stepFour.vue';
    import stepFive from './course/stepFive.vue';
    import stepSix from './course/stepSix.vue';
    import stepSeven from './course/StepSeven.vue';
    import stepEight from './course/stepEight.vue';
    import stepTen from './course/stepTen.vue';
    import stepNine from './course/StepNine.vue';

    export default {
        name: "Registration",
        components: {
            'step-one': stepOne,
            'step-two': stepTwo,
            'step-three': stepThree,
            'step-four': stepFour,
            'step-five': stepFive,
            'step-six': stepSix,
            'step-seven': stepSeven,
            'step-eight': stepEight,
            'step-nine': stepNine,
            'step-ten': stepTen
        },
        props: {
            'course-object': String
        },
        data() {
            return {
                tabIndex: 0,
                max: 100,
                dayNames: [
                    {
                        name: 'Lunedì',
                        shortName: 'L'
                    },
                    {
                        name: 'Martedì',
                        shortName: 'M',
                    },
                    {
                        name: 'Mercoledì',
                        shortName: 'M',
                    },
                    {
                        name: 'Giovedì',
                        shortName: 'G',
                    },
                    {
                        name: 'Venerdì',
                        shortName: 'V',
                    },
                    {
                        name: 'Sabato',
                        shortName: 'S',
                    },
                    {
                        name: 'Domenica',
                        shortName: 'D'
                    }],
                course: {
                    courseId: null,
                    sportId: null,
                    name: null,
                    slug: null,
                    address: null,
                    addressComponents: null,
                    latitude: null,
                    longitude: null,
                    hasFederation: false,
                    federationId: null,
                    startDate: null,
                    endDate: null,
                    startAge: null,
                    endAge: null,
                    selectedLevel: 'Principiante',
                    subscriptions: [],
                    includedServices: [],
                    excludedServices: [],
                    lessonPackages: [],
                    includedLessonServices: [],
                    documents: [],
                    is_for_male: null,
                    is_for_female: null,
                },
                courseDays: [],
                responsible: {
                    name: null,
                    email: null,
                    filePath: '',
                    hasEmail: false
                },
                audience: {
                    forAbleBodied: false,
                    forDisabled: false,
                    checkedDisabilities: [],
                    checkedServices: []
                },
                offer: {
                    quote: null,
                    isIncluded: false,
                    hasTrial: true,
                    hasSubscription: true,
                    hasLessons: true
                },
                lesson: {
                    isDuringCourseTime: true,
                    specificDate: {
                        date: null,
                        time: {
                            hours: 0,
                            minutes: 0
                        }
                    },
                    isFreeLesson: true,
                    price: null,
                    hasEquipments: false,
                    equipments: null,
                },
                description: {
                    text: null,
                    photo: '',
                    filePath: '',
                    gallery: []
                }
            }
        },
        computed: {
            step() {
                return this.tabIndex + 1;
            },
            counter() {
                return this.step * 10;
            }
        },
        mounted() {
            if (window.location.hash) {
                this.tabIndex = window.location.hash.substr(5) - 1;
            }
            if (this.courseObject) {
                this.setCourse(JSON.parse(this.courseObject));
            }
            if (this.course.courseId === null) {
                this.tabIndex = 0;
                window.location.hash = '#step1';
            }
        },
        updated() {
            this.hideTabEight();
            this.hideTabNine();
        },
        methods: {
            postForm() {
                this.$emit('postForm');
            },
            updateStep(step, currentStep = null) {
                let isForward = currentStep && step > currentStep;
                step = this.calculateNextStep(step, isForward);
                this.tabIndex = step - 1;
            },
            calculateNextStep(step, isForward) {
                let possibleSteps = [8, 9]
                if (possibleSteps.indexOf(step) !== -1) {
                    if (step == '8') {
                        if (this.offer.hasLessons) {
                            return step;
                        }
                    }

                    if (step == '9') {
                        if (this.offer.hasSubscription) {
                            return step;
                        }
                    }
                    step = isForward ? step + 1 : step - 1;
                    step = this.calculateNextStep(step, isForward);
                    return step;
                }
                return step;


            },
            updateCourseId(courseId) {
                this.course.courseId = courseId;
            },
            setCourse(course) {
                this.course = {
                    courseId: course.id,
                    sportId: course.sport_id,
                    name: course.name,
                    slug: course.slug,
                    address: course.address,
                    addressComponents: null,
                    latitude: parseFloat(course.latitude),
                    longitude: parseFloat(course.longitude),
                    hasFederation: course.federation_id !== null,
                    federationId: course.federation_id,
                    startDate: course.start_date,
                    endDate: course.end_date,
                    startAge: course.start_age,
                    endAge: course.end_age,
                    selectedLevel: course.level,
                    subscriptions: [],
                    lessonPackages: [],
                    includedLessonServices: [],
                    excludedLessonServices: [],
                    is_for_male: course.is_for_male == 1 ? true : false,
                    is_for_female: course.is_for_female == 1 ? true : false,
                }
                this.initCourseDays(course.days);
                this.initResponsible(course);
                this.initStepFour(course);
                this.initOffer(course);
                this.initLesson(course);
                this.initSubscription(course);
                this.initLessonPackage(course.lesson_packages, course.lesson_package_services);
                this.initDescription(course);
            },
            initCourseDays(days) {
                days.forEach((day) => {
                    let dayIndex = this.dayNames.map((dayName) => {
                        return dayName.name;
                    }).indexOf(day.day);

                    if (dayIndex !== -1) {
                        let time = {
                            index: dayIndex,
                            name: this.dayNames[dayIndex].name,
                            shortName: this.dayNames[dayIndex].shortName,
                            validationError: false,
                            startTime: {
                                hours: parseInt(day.start_time.substr(0, 2)),
                                minutes: parseInt(day.start_time.substr(3, 2))
                            },
                            endTime: {
                                hours: parseInt(day.end_time.substr(0, 2)),
                                minutes: parseInt(day.end_time.substr(3, 2))
                            }
                        };
                        this.courseDays.push(time);
                    }
                });
            },
            initResponsible(course) {
                this.responsible = {
                    name: course.responsible_name,
                    email: course.responsible_email,
                    hasEmail: course.responsible_email !== null
                }
            },
            initStepFour(course) {
                this.audience = {
                    forAbleBodied: course.for_able_bodied == '1',
                    forDisabled: course.disabilities.length > 0,
                    checkedDisabilities: course.disabilities.map((disability) => {
                        return disability.id;
                    }),
                    checkedServices: course.services.map((service) => {
                        return service.id;
                    }),
                };
            },
            initOffer(course) {
                this.offer = {
                    quote: course.membership_fee,
                    isIncluded: course.membership_fee_included == '1',
                    hasTrial: course.has_trial_lesson == '1',
                    hasSubscription: course.has_subscriptions == '1',
                    hasLessons: course.has_lesson_packages == '1'
                };
            },
            initLesson(course) {
                this.lesson = {
                    isDuringCourseTime: course.lesson_during_course_time == '1',
                    isFreeLesson: course.lesson_price == 0,
                    price: course.lesson_price,
                    equipments: course.lesson_equipments,
                    specificDate: {
                        date: null,
                        time: {
                            hours: 0,
                            minutes: 0
                        }
                    }
                };
                if (course.lesson_date_time) {
                    this.lesson.specificDate = {
                        date: course.lesson_date_time,
                        time: {
                            hours: parseInt(course.lesson_date_time.substr(11, 2)),
                            minutes: parseInt(course.lesson_date_time.substr(14, 2))
                        }
                    };
                }
            },
            initSubscription(course) {
                course.subscriptions.forEach((subscription) => {
                    this.course.subscriptions.push({
                        subscriptionId: subscription.id,
                        price: subscription.pivot.price,
                        registrationDeadline: subscription.pivot.registration_deadline,
                        validationError: {
                            subscriptionId: false,
                            price: false,
                            registrationDeadline: false
                        }
                    });
                });
                this.course.includedServices = course.subscription_services.filter((service) => {
                    if (service.pivot.is_excluded != '1') {
                        return service;
                    }
                }).map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
                this.course.excludedServices = course.subscription_services.filter((service) => {
                    if (service.pivot.is_excluded == '1') {
                        return service;
                    }
                }).map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
            },
            initLessonPackage(lessonPackages, lessonServices) {
                lessonPackages.forEach((lessonPackage) => {
                    let lessonPackageObject = {
                        nLessons: lessonPackage.n_lessons,
                        price: lessonPackage.price,
                        startDate: lessonPackage.start_date,
                        endDate: lessonPackage.end_date,
                        validationError: {
                            nLessons: false,
                            price: false,
                            startDate: false,
                            endDate: false
                        }
                    };
                    this.course.lessonPackages.push(lessonPackageObject);
                });
                this.course.includedLessonServices = lessonServices.filter((service) => {
                    if (service.pivot.is_excluded != '1') {
                        return service;
                    }
                }).map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
                this.course.excludedLessonServices = lessonServices.filter((service) => {
                    if (service.pivot.is_excluded == '1') {
                        return service;
                    }
                }).map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
            },
            initDescription(course) {
                this.description = {
                    text: course.description,
                    photo: '',
                    filePath: course.course_image ? course.course_image : '',
                    gallery: course.gallery_images.map((image) => {
                        return image.file_path;
                    })
                }
            },
            hideTabEight() {
                let linkElement = document.getElementById('tab-eight___BV_tab_button__');
                if (linkElement) {
                    let tabElement = linkElement.parentElement;
                    if (this.offer.hasLessons) {
                        tabElement.style.display = 'block';
                    }
                    else {
                        tabElement.style.display = 'none';
                    }
                }
            },
            hideTabNine() {
                let linkElement = document.getElementById('tab-nine___BV_tab_button__');
                if (linkElement) {
                    let tabElement = linkElement.parentElement;
                    if (this.offer.hasSubscription) {
                        tabElement.style.display = 'block';
                    }
                    else {
                        tabElement.style.display = 'none';
                    }
                }
            }
        },
        watch: {
            tabIndex(newIndex) {
                window.location.hash = '#step' + (newIndex + 1);
            },
            'offer.hasLessons'() {
                this.hideTabEight();
            },
            'offer.hasSubscription'() {
                this.hideTabNine();
            }
        }
    }
</script>

<style lang="scss">
    .card-header {
        background-color: white;
        border-bottom: none;
        padding: 15px 15px 5px 15px;

        .nav-tabs .nav-item {
            text-transform: uppercase;
            border-right: 1px solid #DDDDDD;
            height: 20px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .nav-tabs .nav-item a {
            color: black;
            margin-top: -10px;
        }

        .nav-tabs .nav-link.active {
            border: none;
            background-color: white;
            font-weight: bold;
            .tab-title {
                border-bottom: 3px solid #f4812e !important;
            }
        }
        .nav-link.active:after {
            display: inline-block;
        }

        .nav-tabs .nav-link:hover {
            border: none;
        }

        .nav-item:nth-child(4) {
            border-left: none !important;
            padding-left: 0px;
        }
    }
</style>