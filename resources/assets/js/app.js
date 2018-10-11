/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

require('./bootstrap');
require("@babel/polyfill");

import axios from 'axios';

axios.defaults.baseURL = window.baseUrl + '/api/';

import Autocomplete from 'v-autocomplete'
import 'v-autocomplete/dist/v-autocomplete.css'

import MaskedInput from 'vue-text-mask'
import * as VueGoogleMaps from 'vue2-google-maps'

import store from './store/index'

import 'vue-bs-drawer/dist/vue-bs-drawer.min.css'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(require('bootstrap-vue'));

var VueValidation = require('vee-validate');
var VueValidationIt = require('vee-validate/dist/locale/it');

Vue.use(VueValidation, {
    events: 'change',
    locale: 'it',
    dictionary: {
        it: VueValidationIt
    }
});

Vue.use(Autocomplete);

Vue.use(VueGoogleMaps, {
    load: {
        key: window.googleMapsAPI,
        libraries: 'places'
    }
});


// Function for using laravel validation with vee-validate
Vue.prototype.$setErrorsFromResponse = function (errorResponse, formName = null) {
    // only allow this function to be run if the validator exists
    if (!this.hasOwnProperty('$validator')) {
        return;
    }

    // clear errors
    this.$validator.errors.clear();

    // check if errors exist
    if (!errorResponse.hasOwnProperty('errors')) {
        return;
    }

    let errorFields = Object.keys(errorResponse.errors);

    // insert laravel errors
    errorFields.map(field => {
        let errorString = errorResponse.errors[field].join(', ');
        if (formName) {
            this.$validator.errors.add(formName + '.' + field, errorString);
        }
        else {
            this.$validator.errors.add(field, errorString);
        }
    });
};

Vue.mixin({
    data() {
        return {
            commonWeekDays: [
                {value: 0, long: 'Domenica', short: 'DOM'},
                {value: 1, long: 'Lunedì', short: 'LUN'},
                {value: 2, long: 'Martedì', short: 'MAR'},
                {value: 3, long: 'Mercoledì', short: 'MER'},
                {value: 4, long: 'Giovedì', short: 'GIO'},
                {value: 5, long: 'Venerdì', short: 'VEN'},
                {value: 6, long: 'Sabato', short: 'SAB'}
            ],
            commonMonths: [
                {value: 0, name: 'Gennaio'},
                {value: 1, name: 'Febbraio'},
                {value: 2, name: 'Marzo'},
                {value: 3, name: 'Aprile'},
                {value: 4, name: 'Maggio'},
                {value: 5, name: 'Giugno'},
                {value: 6, name: 'Luglio'},
                {value: 7, name: 'Agosto'},
                {value: 8, name: 'Settembre'},
                {value: 9, name: 'Ottobre'},
                {value: 10, name: 'Novembre'},
                {value: 11, name: 'Dicembre'}
            ]
        }
    },
    methods: {
        handleError(error) {
            const data = error.data;
            let message = '';
            if (data.hasOwnProperty('errors')) {
                message += '<ul class="list-unstyled mb-0">';
                for (let key in data.errors) {
                    message += `<li><span class="text-capitalize">${key}</span>: ${data.errors[key]}</li>`;
                }
                message += '</ul>';
            } else if (data.hasOwnProperty('error')) {
                message = data.error;
            } else {
                message = 'Qualcosa &egrave; andato storto!';
            }
            window.alerts.error(message);
        }
    }
});

Vue.component('masked-input', MaskedInput)
Vue.component('google-map', VueGoogleMaps.Map)
Vue.component('google-map-marker', VueGoogleMaps.Marker)
/*

*/
Vue.component('sportsclubs', require('./components/SportsClubs.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));
Vue.component('vue-footer', require('./components/Footer.vue'));
Vue.component('company-dashboard', require('./components/company/Dashboard.vue'));
Vue.component('company-detail', require('./components/company/Detail.vue'));
Vue.component('registration', require('./components/Registration.vue'));
// Vue.component('reservation-box', require('./components/reservation/ReservationBox.vue'));
Vue.component('course-reservation-box', require('./components/reservation/CourseReservationBox.vue'));
Vue.component('user-profile', require('./components/user/Profile.vue'));
Vue.component('user-register', require('./components/auth/UserRegister.vue'));
Vue.component('user-register-page', require('./components/auth/UserRegister.Page.vue'));
Vue.component('user-login', require('./components/auth/UserLogin.vue'));
Vue.component('user-verify', require('./components/auth/UserVerify.vue'));
Vue.component('go-to-dashboard-button', require('./components/course/GoDashboardButton.vue'));
Vue.component('sportsman', require('./components/reservation/ReservationSportsman.vue'));
Vue.component('reservation-success', require('./components/reservation/ReservationSuccess.vue'));
Vue.component('search-view', require('./components/search/SearchView.vue'));
Vue.component('homepage', require('./components/home/Homepage'));
Vue.component('custom-selector', require('./components/shared/CustomSelector'));
Vue.component('cart-tabs', require('./components/cart/CartTabs.vue'));
Vue.component('cart', require('./components/cart/Cart.vue'));
Vue.component('friend-form', require('./components/cart/FriendForm.vue'));
Vue.component('payment-form', require('./components/cart/PaymentForm.vue'));
Vue.component('payment-summary', require('./components/cart/PaymentSummary.vue'));
Vue.component('page-clip-loader', require('./components/spinners/PageClipLoader.vue'));
Vue.component('element-clip-loader', require('./components/spinners/ElementClipLoader.vue'));
Vue.component('show-course', require('./components/course/ShowCourse.vue'));
Vue.component('about-us', require('./components/AboutUs.vue'));

require('./vue-filters');

Vue.prototype.$bus = new Vue();

const app = new Vue({
    store,
    el: '#app'
});

require('./fancybox');
