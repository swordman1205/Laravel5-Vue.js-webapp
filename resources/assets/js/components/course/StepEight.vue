<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        components: {
            'datepicker': Datepicker
        },
        name: "StepEight",
        props: {
            'course': Object,
            'offer': Object,
            'company': Object,
        },
        data() {
            return {
                loading: false,
                services: [],
                includedServiceTag: '',
                excludedServiceTag: ''
            }
        },
        mounted() {
            if (this.course.lessonPackages.length < 1) {
                this.addLessonPackage();
            }
            axios({
                method: 'get',
                url: '/subscription-services'
            }).then((response) => {
                this.services = response.data.map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
            }).catch((error) => {
                this.message = 'Error';
            });

        },
        computed: {
            filteredIncludedServices() {
                let serviceIds = this.course.excludedServices.map((excludedService) => {
                    return excludedService.value
                });
                let filteredServices = this.services.filter((service) => {
                    if (serviceIds.indexOf(service.value) === -1) {
                        return service;
                    }
                });
                return filteredServices.filter(service => new RegExp(this.includedServiceTag, 'i').test(service.text));
            },
            filteredExcludedServices() {
                let serviceIds = this.course.includedLessonServices.map((includedService) => {
                    return includedService.value
                });
                let filteredServices = this.services.filter((service) => {
                    if (serviceIds.indexOf(service.value) === -1) {
                        return service;
                    }
                });
                return filteredServices.filter(service => new RegExp(this.excludedServiceTag, 'i').test(service.text));
            }
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            addLessonPackage() {
                this.course.lessonPackages.push({
                    nLessons: null,
                    price: null,
                    startDate: null,
                    endDate: null,
                    validationError: {
                        nLessons: false,
                        price: false,
                        startDate: false,
                        endDate: false
                    }
                })
            },
            removeLessonPackage(index) {
                this.course.lessonPackages.splice(index, 1);
            },
            previousStep() {
                this.$emit('update-step', 7, 8);
            },
            postForm() {
                if (this.validation()) {
                    this.loading = true;
                    this.$bus.$emit('searching');

                    axios({
                        method: 'put',
                        url: '/courses/' + this.course.courseId + '/lesson-packages',
                        data: {
                            lessonPackages: this.course.lessonPackages,
                            includedLessonServices: this.course.includedLessonServices.map(service => service.value),
                            excludedLessonServices: this.course.excludedServices.map(service => service.value)
                        },
                    }).then((response) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');

                        if (this.offer.hasSubscription) {
                            this.$emit('update-step', 9, 8);
                            return;
                        }

                        window.location = '/societa_sportive/' + this.company.slug +'/dashboard'
                    }).catch((error) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.message = 'Error';
                    });
                }
            },
            validation() {
                return true;
                s
            }

        }
    }
</script>
<style scoped>

</style>
