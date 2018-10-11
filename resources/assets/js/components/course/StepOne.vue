<script>
    import axios from 'axios'
    import * as VueGoogleMaps from 'vue2-google-maps';
    import vSelect from 'vue-select'
    import Vue from 'vue'

    if (window.googleMapsAPI) {
        Vue.use(VueGoogleMaps, {
            load: {
                key: window.googleMapsAPI,
                libraries: 'places',
            }
        });
    }
    export default {
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            'google-map': VueGoogleMaps.Map,
            'google-marker': VueGoogleMaps.Marker,
            'v-select': vSelect
        },
        name: "stepOne",
        props: { 'course': Object, 'companies': null, 'companyprop': null },
        data() {
            return {
                loading: false,
                sport: null,
                message: null,
                types: ['geocode'],
                sports: [],
                federations: [],
                company: null
            }
        },
        computed: {
            center() {
                if (this.course.latitude && this.course.longitude) {
                    return {
                        lat: this.course.latitude,
                        lng: this.course.longitude
                    }
                }
            }
        },
        mounted() {
            var self = this;

            this.getFederations();

            this.companies.forEach(function (company) {
                if (self.course.company_id === company.id) {
                    self.company = company;
                }
            });

            if (this.companyprop !== null) {
                this.company = this.companyprop;
            }
        },
        watch: {
            'course.sportId'() {
                if (!this.sport) {
                    this.getSport(this.course.sportId);
                }
            },
            'sport'() {
                if (this.sport) {
                    this.course.sportId = this.sport.id;
                }
            }
        },
        methods: {
            onSearch(search, loading) {
                this.search(loading, search, this);
            },
            search: _.debounce((loading, search, vm) => {
                axios({
                    method: 'get',
                    url: '/sports/search?q=' + search
                }).then((response) => {
                    vm.sports = response.data.sports;
                    loading(false);

                }).catch((error) => {
                    this.message = 'Error';
                    loading(false);
                });
            }),
            getSport(sportId) {
                axios({
                    method: 'get',
                    url: '/sports/' + sportId
                }).then((response) => {
                    this.sport = response.data.sport;

                }).catch((error) => {
                    this.message = 'Error';
                });
            },
            getFederations() {
                axios({
                    method: 'get',
                    url: '/federations'
                }).then((response) => {
                    this.federations = response.data.federations;

                }).catch((error) => {
                    this.message = 'Error';
                });
            },
            triggerPlaceChanged() {
                const el = this.$refs.addressComponent.$refs.input;
                el.focus();
                const ev = new Event('keydown');
                ev.which = 40;
                ev.keyCode = 40;
                el.dispatchEvent(ev);

            },
            isLoading() {
                return this.loading;
            },
            getAddressData(payload) {
                this.course.address = payload.formatted_address;
                this.course.addressComponents = payload.address_components;
                this.course.latitude = payload.geometry.location.lat();
                this.course.longitude = payload.geometry.location.lng();
            },
            postForm() {
                this.loading = true;
                this.$bus.$emit('searching');

                if (this.course.hasFederation && this.course.federationId == null) {
                    this.message = 'Seleziona una federazione';

                    this.loading = false;
                    this.$bus.$emit('filtered');
                } else {
                    if (this.course.addressComponents === null) {
                        this.triggerPlaceChanged();
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        return;
                    }
                    this.course.sportId = this.sport.id;
                    this.course.companyId = this.company.id;

                    if (this.course.courseId) {
                        axios({
                            method: 'put',
                            url: '/courses/' + this.course.courseId,
                            data: {
                                course: this.course
                            }
                        }).then((response) => {
                            this.loading = false;
                            this.$bus.$emit('filtered');
                            this.$emit('update-step', 2);
                        }).catch((error) => {
                            this.loading = false;
                            this.$bus.$emit('filtered');
                            this.message = 'Error';
                        });
                    }
                    else {
                        axios({
                            method: 'post',
                            url: '/courses',
                            data: {
                                course: this.course
                            }
                        }).then((response) => {
                            this.course.slug = response.data.course.slug;
                            this.loading = false;
                            this.$bus.$emit('filtered');
                            this.$bus.$emit('course-created',  response.data.course);
                            this.$emit('update-step', 2);
                            this.$emit('update-course-id', response.data.course.id);
                        }).catch((error) => {
                            this.message = 'Error';
                        });
                    }

                }
            }
        }
    }
</script>
<style scoped>
    label {
        display: inline;
    }

    .custom-control-input {
        background-color: #17BEBB;
        border: 1px solid #adb8c0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
        color: #99a1a7;
    }

    input:checked {
        background-color: #17BEBB;
        border: 1px solid #adb8c0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
        color: #99a1a7;
    }
</style>