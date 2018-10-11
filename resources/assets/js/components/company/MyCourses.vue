<script>
    export default {
        name: "MyCourses",
        props: {
            'courses': Array,
            'sports': {},
            'company': {},
            'companies': null
        },
        data() {
            return {
                modalShow: false,
                duplicationModalShow: false,
                selectedCompany: null,
                //temp variables until we got number of participants
                value: 65,
                max: 100,
                filterSport: null,
                ages: [
                    '0 - 5 anni',
                    '6 - 11 anni',
                    '12 - 17 anni',
                    '18 - 26 anni',
                    '27+ anni'
                ],
                filterAge: null,
                actualAge: null
            }
        },
        mounted() {
        },
        computed: {
            activeDays() {
                let activeDays = [];
                this.courses.forEach((course, index) => {
                    activeDays[index] = [
                        {
                            shortName: 'L',
                            isActive: false
                        }, {
                            shortName: 'M',
                            isActive: false
                        }, {
                            shortName: 'M',
                            isActive: false
                        }, {
                            shortName: 'G',
                            isActive: false
                        }, {
                            shortName: 'V',
                            isActive: false
                        }, {
                            shortName: 'S',
                            isActive: false
                        }, {
                            shortName: 'D',
                            isActive: false
                        }
                    ],
                        course.days.forEach((day) => {
                            if (day.day == 'Lunedì') {
                                activeDays[index][0].isActive = true;
                            }
                            if (day.day == 'Martedì') {
                                activeDays[index][1].isActive = true;
                            }
                            if (day.day == 'Mercoledì') {
                                activeDays[index][2].isActive = true;
                            }
                            if (day.day == 'Giovedì') {
                                activeDays[index][3].isActive = true;
                            }
                            if (day.day == 'Venerdì') {
                                activeDays[index][4].isActive = true;
                            }
                            if (day.day == 'Sabato') {
                                activeDays[index][5].isActive = true;
                            }
                            if (day.day == 'Domenica') {
                                activeDays[index][6].isActive = true;
                            }
                        });
                });
                return activeDays;
            }
        },
        methods: {
            filterBySport(sport) {
                this.filterSport = sport;
            },
            filterByAge(age) {
                this.actualAge = age;
                switch (age) {
                    case '0 - 5 anni':
                        this.filterAge = [0, 5];
                        break;
                    case '6 - 11 anni':
                        this.filterAge = [6, 11];
                        break;
                    case '12 - 17 anni':
                        this.filterAge = [12, 17];
                        break;
                    case '18 - 26 anni':
                        this.filterAge = [18, 26];
                        break;
                    case '27+ anni':
                        this.filterAge = [27, 100];
                        break;
                }
            },
            hiddenCourse(course) {

                var state = 'hidden';
                if (this.filterAge == null || (this.filterAge[0] <= course.start_age && this.filterAge[1] >= course.end_age)) {
                    /*console.log(this.filterAge[0]);
                    console.log(this.filterAge[1]);
                    console.log(course.start_age);
                    console.log(course.end_age);*/
                    state = '';
                }
                if (this.filterSport != null && this.filterSport.id != course.sport.id) {
                    state = 'hidden'
                }
                return state;
            },
            clearSportFilter() {
                this.filterSport = null;
            },
            clearAgeFilter() {
                this.actualAge = 'Tutte le età';
                this.filterAge = null;
            },
            createCourse() {
                window.location.href = '/corsi/create/?company=' + this.company.slug;
            },
            getDayClass(day) {
                if (day.isActive) {
                    return 'company-day company-day-active';
                }
                else
                    return ''
            },
            edit(slug) {
                window.location.href = '/corsi/' + slug + '/edit';

            },
            activate(course, index) {
                if (!course.is_complete) {
                    course.is_active = false;
                    bootbox.confirm({
                        message: "Questo corso è incompleto e non può essere attivato<br>Vuoi completarlo adesso?",
                        buttons: {
                            cancel: {
                                label: 'Chiudi'
                            },
                            confirm: {
                                label: 'Completa Corso',
                                className: 'orange-gradient-button'
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                location.href = '/corsi/' + course.slug + '/edit';
                            }
                        }
                    });
                }
                else {
                    axios({
                        method: 'put',
                        url: '/courses/' + course.id + '/active',
                        data: {
                            is_active: this.courses[index].is_active
                        }
                    }).then((response) => {
                        this.courses[index].is_active = response.data.is_active;
                    }).catch((error) => {
                        this.courses[index].is_active !== this.courses[index].is_active;
                        this.$emit('init-error', 'Active failed');
                    });
                }
            },
            getActiveCourseCheckboxId(id) {
                return 'active-course-checkbox' + id
            },
            duplicateIn(id) {
                var self = this;

                axios({
                    method: 'post',
                    url: '/courses/' + id + '/duplicateIn/' + self.selectedCompany
                }).then((response) => {
                    if (self.selectedCompany == self.company.id) {
                        this.courses.push(response.data.course);
                    }
                    window.alerts.success('Corso duplicato correttamente');
                }).catch((error) => {
                    this.$emit('init-error', 'Operazione fallita');
                });
            },
            duplicate(id) {
                bootbox.confirm({
                    message: "Duplicare il corso selezionato?",
                    buttons: {
                        confirm: {
                            label: 'Sì',
                            className: 'orange-gradient-button'
                        },
                        cancel: {
                            label: 'No',
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            axios({
                                method: 'post',
                                url: '/courses/' + id + '/duplicate'
                            }).then((response) => {
                                this.courses.push(response.data.course);
                                window.alerts.success('Corso duplicato correttamente');
                            }).catch((error) => {
                                this.$emit('init-error', 'Operazione fallita');
                            });
                        }
                    }
                })
            },
            remove(id, index) {
                axios({
                    method: 'delete',
                    url: '/courses/' + id,
                }).then((response) => {
                    this.courses.splice(index, 1);
                    this.modalShow = false;
                }).catch((error) => {
                    this.$emit('init-error', 'Delete failed');
                });
            },
            buildCourseUrl(course) {
                return `/corsi/${course.slug}`;
            }
        }
    }
</script>

<style scoped>

</style>