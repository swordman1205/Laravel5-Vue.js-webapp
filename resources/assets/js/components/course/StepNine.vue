<script>
    import Datepicker from 'vuejs-datepicker';
    import VueTagsInput from '@johmun/vue-tags-input'

    export default {
        components: {
            'datepicker': Datepicker,
            'vue-tags-input': VueTagsInput
        },
        name: "StepNine",
        props: {
            'course': Object,
            'company': Object,
        },
        data() {
            return {
                loading: false,
                subscriptions: [],
                services: [],
                includedServiceTag: '',
                excludedServiceTag: ''
            }
        },
        computed: {
            mappedSubscriptions() {
                let selectedSubscriptionIds = [];
                selectedSubscriptionIds = this.course.subscriptions.map((subscription) => {
                    if (subscription.subscriptionId) {
                        return subscription.subscriptionId;
                    }
                });
                return this.subscriptions.map((subscription) => {
                    return {
                        id: subscription.id,
                        name: subscription.name,
                        disabled: selectedSubscriptionIds.indexOf(subscription.id) > -1
                    }
                });
            },
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
                let serviceIds = this.course.includedServices.map((includedService) => {
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
        mounted() {
            axios({
                method: 'get',
                url: '/subscriptions'
            }).then((response) => {
                this.subscriptions = response.data.subscriptions.map((subscription) => {
                    return {
                        id: subscription.id,
                        name: subscription.name,
                        disabled: false
                    }
                });
                if (this.course.subscriptions.length < 1) {
                    this.addSubscription();
                }
            }).catch((error) => {
                this.message = 'Error';
            });
            axios({
                method: 'get',
                url: '/subscription-services'
            }).then((response) => {
                this.services = response.data.services.map((service) => {
                    return {
                        value: service.id,
                        text: service.name
                    }
                });
            }).catch((error) => {
                this.message = 'Error';
            });
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            addSubscription() {
                this.course.subscriptions.push({
                    subscriptionId: null,
                    price: null,
                    registrationDeadline: null,
                    validationError: {
                        subscriptionId: false,
                        price: false,
                        registrationDeadline: false
                    }
                });
            },
            removeSubscription(index) {
                this.course.subscriptions.splice(index, 1);
            },
            validation() {
                if (this.course.subscriptions.length < 1) {
                    window.alerts.error('Inserire almeno un abbonamento');
                    return;
                }
                let passed = true;
                this.course.subscriptions.forEach((subscription) => {
                    subscription.validationError = {
                        subscriptionId: false,
                        price: false,
                    }
                    if (!subscription.subscriptionId) {
                        subscription.validationError.subscriptionId = true;
                        passed = false;
                    }
                    if (!subscription.price) {
                        subscription.validationError.price = true;
                        passed = false;
                    }
                });
                return passed;
            },
            previousStep() {
                this.$emit('update-step', 6, 9);
            },
            postForm() {
                if (this.validation()) {
                    this.loading = true;
                    this.$bus.$emit('searching');

                    axios({
                        method: 'put',
                        url: '/courses/' + this.course.courseId + '/subscriptions',
                        data: {
                            subscriptions: this.course.subscriptions,
                            includedServices: this.course.includedServices.map(service => service.value),
                            excludedServices: this.course.excludedServices.map(service => service.value)
                        },
                    }).then((response) => {
                        window.location = '/societa_sportive/' + this.company.slug +'/dashboard'
                    }).catch((error) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.message = 'Error';
                    });
                }
            }
        }
    }
</script>
<style scoped>

</style>