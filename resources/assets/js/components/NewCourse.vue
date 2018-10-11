<script>
    import axios from 'axios'
    import * as VueGoogleMaps from 'vue2-google-maps';

    export default {
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            'google-map': VueGoogleMaps.Map,
            'google-marker': VueGoogleMaps.Marker
        },
        name: "newCourse",
        data() {
            return {
                message: null,
                types: ['geocode'],
                center: null,
                course: {
                    name: null,
                    address: null,
                    addressComponents: null,
                    latitude: null,
                    longitude: null,
                    hasFederation: false,
                    federationId: null
                },
                federations: [
                    {
                        id: 1,
                        name: 'federation 1'
                    }, {
                        id: 2,
                        name: 'federation 2'
                    }, {
                        id: 3,
                        name: 'federation 3'
                    }
                ]
            }
        },
        mounted() {
            axios({
                method: 'get',
                url: '/federations'
            }).then((response) => {
                this.federations = response.data.federations;
            }).catch((error) => {
                this.message = 'Error';
            });
        },
        methods: {
            getAddressData(payload) {
                this.course.address = payload.formatted_address;
                this.course.addressComponents = payload.address_components;
                this.course.latitude = payload.geometry.location.lat();
                this.course.longitude = payload.geometry.location.lng();
                this.center = payload.geometry.location;
            },
            postForm() {
                if (this.course.hasFederation && this.course.federationId == null) {
                    this.message = 'Pick a federation';
                }
                else {
                    axios({
                        method: 'post',
                        url: '/courses/',
                        data: {
                            course: this.course
                        }
                    }).then((response) => {
                        this.$emit('update-step', 2);
                        this.$emit('update-course-id', response.data.course.id);
                    }).catch((error) => {
                        this.message = 'Error';
                    });
                }
            }
        }
    }
</script>
<style scoped>

</style>