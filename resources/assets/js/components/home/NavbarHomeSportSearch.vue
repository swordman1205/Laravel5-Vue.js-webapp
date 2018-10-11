<template>
    <b-input-group class="navbar-home-sport-search">
        <b-form-input v-model="search_string"
                      class="navbar-search-world-input home-sport-search__input py-2 border-right-0 border"
                      placeholder="Cerca per sport o per luogo"></b-form-input>
        <span class="input-group-append">
            <b-button :href="getSearchUrl()" class="btn btn-outline-secondary search-world-navbar-button border-left-0 border">
                <i class="fa fa-search"></i>
            </b-button>
        </span>
    </b-input-group>
</template>

<script>
    export default {
        name: 'HomeSportSearch',
        props: {
            url: String
        },
        data() {
            return {
                search_string: null,
                coords: []
            }
        },
        mounted() {

            this.search_string = this.getParameterByName('search_string')

            this.getLocation();
            $(".navbar-search-world-input").keyup(function (event) {
                event.preventDefault();
                if (event.keyCode == 13) {
                    window.location = $(".search-world-navbar-button").attr("href");
                }
            });
        },
        methods: {
            getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');

                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);

                if (!results) return null;
                if (!results[2]) return '';

                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getSearchUrl() {
                if (this.coords.length != 0) {
                    return this.url +
                        '?search_string=' +
                        this.search_string +
                        '&latitude=' +
                        this.coords[0] +
                        '&longitude=' +
                        this.coords[1]
                } else {
                    return this.url +
                        '?search_string=' +
                        this.search_string +
                        '&latitude=' +
                        null +
                        '&longitude=' +
                        null
                }
            },
            getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(this.returnPosition);
                }
            },
            returnPosition(position) {
                this.coords.push(position.coords.latitude);
                this.coords.push(position.coords.longitude);

                this.$bus.$emit('geolocalizated', {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                });
            },
        }
    }
</script>
