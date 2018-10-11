<script>
    import * as VueGoogleMaps from 'vue2-google-maps';
    import howorangogowork from './SportClubs-partialsComponents/howOrangogoWork'
    import contactform from './SportClubs-partialsComponents/ContactForm'
    import reviews from './SportClubs-partialsComponents/Reviews'
    import VueTagsInput from '@johmun/vue-tags-input'

    if (window.googleMapsAPI) {
        Vue.use(VueGoogleMaps, {
            load: {
                key: window.googleMapsAPI,
                libraries: 'places',
            }
        });
    }

    export default {
        name: "SportsClubs",
        props: ['userprop'],
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            'vue-tags-input': VueTagsInput,
            'howorangogowork': howorangogowork,
            'contactform': contactform,
            'reviews': reviews,
        },
        data() {
            return {
                counter: 0,
                max: 100,
                intervalID: 100,
                sports: [],
                errored: false,
                selected: undefined,
                step: 1,
                accept_field: false,
                registration: {
                    sport_id: '',
                    selectedSport: '',
                    email: '',
                    club: '',
                    address: '',
                    password: ''
                },
                types: ['geocode'],
                user: {
                    email: '',
                    password: '',
                },
                company: {
                    name: '',
                    sport_id: null
                },
                apiErrors: null,
                tag: '',
                tags: [],
                autocompleteItems: [],
                debounce: null,
                validation: [{
                    type: 'min-length',
                    rule: /^.{4,}$/,
                }],
                companyAddress: {
                    address: null,
                    addressComponents: [],
                    latitude: null,
                    longitude: null,
                }
            }
        },
        mounted() {
            $('#registration-form').submit(function (event) {
                event.preventDefault();
            });

            if (this.userprop) {
                this.user = this.userprop
            }
        },
        created() {
            axios
                .get("sports/search")
                .then(response => {
                    this.sports = response.data.sports;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true;
                })
        },
        methods: {
            openCompleteRegistrationModal() {
                $('#confirmRegistration').modal({backdrop: 'static', keyboard: false});
            },
            checkValidationForm() {
                var state = true;

                if (this.company.name == '') {
                    $('#registration-company__name-field').addClass('border-danger');
                    state = false;
                } else {
                    $('#registration-company__name-field').removeClass('border-danger');
                }

                if (this.companyAddress.address == null) {
                    $('#registration-company__address-field').addClass('border-danger');
                    state = false;
                }
                else {
                    $('#registration-company__address-field').removeClass('border-danger');
                }

                if (!this.userprop) {
                    if (this.user.email == '') {
                        $('#registration-company__email-field').addClass('border-danger');
                        state = false;
                    }
                    else {
                        $('#registration-company__email-field').removeClass('border-danger');
                    }
                    if (this.user.password == '') {
                        $('#registration-company__password-field').addClass('border-danger');
                        state = false;
                    }
                    else {
                        $('#registration-company__password-field').removeClass('border-danger');
                    }
                }

                if (!this.accept_field) {
                    $('#registration-company__accept-field').parent().addClass('border-danger-checkbox');
                    state = false;
                }

                else {
                    $('#registration-company__accept-field').removeClass('border-danger');
                }

                return state;
            },
            registerCompany() {
                var self = this;

                if (this.checkValidationForm()) {
                    $('#spinner-modal').modal();
                    axios({
                        method: 'post',
                        url: '/companies/register',
                        data: {
                            company: self.company,
                            companyAddress: self.companyAddress,
                            user: self.user
                        }
                    }).then((response) => {
                        if (response.data) {
                            self.openCompleteRegistrationModal();
                            self.company = response.data;
                        }
                        $('#spinner-modal').modal('hide');
                    }).catch((error) => {
                        $('#spinner-modal').modal('hide');
                        if (typeof error.response.data.message != "undefined" && error.response.data.message == "duplicate_entry")
                                window.toastr.warning("Questo indirizzo email risulta gia' registrato. Effettua il login con la tua password");
                        else if (typeof error.response.data.message != "undefined")
                            window.toastr.error("Errore durante la registrazione. Riprova piu tardi");
                        else if (error.response.data.errors) {
                            this.step = 2;
                            this.apiErrors = error.response.data.errors;
                        }
                    });
                }
            },
            update(newTags) {
                this.autocompleteItems = [];
                this.tags = newTags;
            },
            initItems() {
                if (this.tag.length === 0) return;
                const url = 'sports/search?q=';

                axios.get(url + this.tag).then(response => {
                    this.autocompleteItems = response.data.sports.map(a => {
                        return {
                            text: a.name,
                            id: a.id
                        };
                    });
                }).catch(() => console.warn('Oh. Something went wrong'));
            },
            backLink() {
                this.step--;
                if (this.counter > 0) {
                    this.counter -= 15;
                }
                this.intervalID = setInterval(() => {
                    this.counter = (this.counter) % this.max
                }, 9999999999)
            },
            submitformEmail() {
                // Send a POST request
                if (this.user && this.user.email) {
                    axios({
                        method: 'put',
                        url: '/users/' + this.user.id + '/email',
                        data: {
                            email: this.registration.email
                        }
                    }).then((response) => {
                        this.user = response.data.user;
                        this.step = 2;
                        this.apiErrors = null;
                        if (this.counter < 100) {
                            this.counter += 15;
                        }
                        this.intervalID = setInterval(() => {
                            this.counter = (this.counter) % this.max
                        }, 9999999999)
                    })
                        .catch((error) => {
                            if (error.response.data.errors) {
                                this.step = 1;
                                this.apiErrors = error.response.data.errors;
                            }
                        });
                } else {
                    axios({
                        method: 'post',
                        url: '/companies',
                        data: {
                            typology_id: 1,
                            sport_id: this.registration.selectedSport.id,
                            user: {
                                email: this.registration.email
                            }
                        }
                    }).then((response) => {
                        this.user = response.data.user;
                        this.company = response.data.company;
                        this.step = 2;
                        this.apiErrors = null;
                        if (this.counter < 100) {
                            this.counter += 15;
                        }
                        this.intervalID = setInterval(() => {
                            this.counter = (this.counter) % this.max
                        }, 9999999999)
                    })
                        .catch((error) => {
                            if (error.response.data.errors) {
                                this.step = 1;
                                this.apiErrors = error.response.data.errors;
                            }
                        });
                }
            },
            submitformClub() {
                // Send a PUT request
                axios({
                    method: 'put',
                    url: '/companies/' + this.company.id + '/name',
                    data: {
                        name: this.registration.club
                    }
                }).then((response) => {
                    this.company = response.data.company;
                    this.step = 3;
                    if (this.counter < 100) {
                        this.counter += 15;
                    }
                    this.intervalID = setInterval(() => {
                        this.counter = (this.counter) % this.max
                    }, 9999999999)
                }).catch((error) => {
                    if (error.response.data.errors) {
                        this.step = 2;
                        this.apiErrors = error.response.data.errors;
                    }
                });
            },
            getAddressData: function (payload) {
                this.registration.address = payload.formatted_address;
                this.registration.addressComponents = payload.address_components;
                this.registration.latitude = payload.geometry.location.lat();
                this.registration.longitude = payload.geometry.location.lng();
            },
            getCompanyAddressData: function (payload) {
                this.companyAddress.address = payload.formatted_address;
                this.companyAddress.addressComponents = payload.address_components;
                this.companyAddress.latitude = payload.geometry.location.lat();
                this.companyAddress.longitude = payload.geometry.location.lng();
            },
            submitformAddress() {
                // Send a PUT request
                axios({
                    method: 'put',
                    url: '/companies/' + this.company.id + '/address',
                    data: {
                        address: this.registration.address,
                        addressComponents: this.registration.addressComponents,
                        latitude: this.registration.latitude,
                        longitude: this.registration.longitude
                    }
                }).then(() => {
                    this.step = 4;
                    if (this.counter < 100) {
                        this.counter += 15;
                    }
                    this.intervalID = setInterval(() => {
                        this.counter = (this.counter) % this.max
                    }, 9999999999)
                }).catch((error) => {
                    if (error.response.data.errors) {
                        this.step = 3;
                        this.apiErrors = error.response.data.errors;
                    }
                });
            },
            submitformTags() {
                // Send a POST request
                axios({
                    method: 'post',
                    url: '/companies/' + this.company.id + '/sports',
                    data: {
                        sports: this.tags
                    }
                }).then(() => {
                    this.step = 5;
                    if (this.counter < 100) {
                        this.counter += 15;
                    }
                    this.intervalID = setInterval(() => {
                        this.counter = (this.counter) % this.max
                    }, 9999999999)
                })
                    .catch(() => {

                    });
            },
            getCompanyDashboardUrl() {
                return `societa_sportive/${this.company.slug}/dashboard`;
            },
            getCourseCreateUrl() {
                return 'course/create';
            },
            submitformPassword() {
                // Send a POST request
                axios({
                    method: 'post',
                    url: ' /api/users/{user_id}/password',
                    data: {
                        user: {
                            user_password: this.registration.password
                        }
                    }
                }).then(() => {
                    // In the end with success
                    alert('Error')
                })
                    .catch(() => {
                        this.step = 6;
                        if (this.counter < 100) {
                            this.counter += 15;
                        }
                        this.intervalID = setInterval(() => {
                            this.counter = (this.counter) % this.max
                        }, 9999999999)
                    });
            }
        },
        watch: {
            'tag': 'initItems',
        },
    }
</script>
<style>
    .pac-container {
        z-index: 1051 !important;
    }
</style>