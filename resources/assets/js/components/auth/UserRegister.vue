<template>
    <b-modal v-model="isVisible" hide-footer title="Registrati">
        <div class="modal-user-register">
            <h2 class="tc">Sei uno sportivo?</h2>
            <p class="tc">Registrati subito</p>
            <div class="modal-user-register__mask" v-if="loading">
                <spinner message="Registering..."></spinner>
            </div>
            <p class="modal-user-register__register mb-0">Accedi con <a :href="googleUrl">Google</a> o <a
                    :href="facebookUrl">Facebook</a></p>
            <div class="modal-user-register__divider pt-3 pb-3"></div>
            <b-form @submit.prevent="doRegister" data-vv-scope="register-form">
                <b-form-group label-for="registerEmail"
                              :state="!errors.first('register-form.email')"
                              :invalid-feedback="errors.first('register-form.email')">
                    <b-form-input id="registerEmail"
                                  name="email"
                                  type="email"
                                  v-validate="'required|email'"
                                  v-model="form.email"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('register-form.email')}"
                                  placeholder="Indirizzo email">
                    </b-form-input>
                </b-form-group>
                <b-form-group label-for="registerFirstName"
                              :state="!errors.first('register-form.first-name')"
                              :invalid-feedback="errors.first('register-form.first-name')">
                    <b-form-input id="registerFirstName"
                                  name="first-name"
                                  type="text"
                                  v-validate="'required'"
                                  v-model="form.first_name"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('register-form.first-name')}"
                                  placeholder="Nome">
                    </b-form-input>
                </b-form-group>
                <b-form-group label-for="registerLastName"
                              :state="!errors.first('register-form.last-name')"
                              :invalid-feedback="errors.first('register-form.last-name')">
                    <b-form-input id="registerLastName"
                                  name="last-name"
                                  type="text"
                                  v-validate="'required'"
                                  v-model="form.last_name"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('register-form.last-name')}"
                                  placeholder="Cognome">
                    </b-form-input>
                </b-form-group>
                <b-form-group label-for="registerPassword"
                              :state="!errors.first('register-form.password')"
                              :invalid-feedback="errors.first('register-form.password')">
                    <b-form-input id="registerPassword"
                                  name="password"
                                  type="password"
                                  ref="password"
                                  v-validate="'required'"
                                  v-model="form.password"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('register-form.password')}"
                                  placeholder="Password">
                    </b-form-input>
                </b-form-group>
                <b-form-group label-for="registerPasswordConfirm"
                              :state="!errors.first('register-form.password-confirm')"
                              :invalid-feedback="errors.first('register-form.password-confirm')">
                    <b-form-input id="registerPasswordConfirm"
                                  name="password-confirm"
                                  type="password"
                                  v-validate="'required|confirmed:$password'"
                                  v-model="form.password_confirmation"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('register-form.password-confirm')}"
                                  placeholder="Conferma Password">
                    </b-form-input>
                </b-form-group>
                <p class="font-weight-bold mb-1">DATA DI NASCITA</p>
                <div class="d-flex justify-content-between mb-2">
                    <div class="flex-fill mr-2">
                        <b-form-group label-for="registerDayOfBirthDate"
                                      :state="!errors.first('register-form.birth-date')"
                                      :invalid-feedback="errors.first('register-form.birth-date')">
                            <b-form-select id="registerDayOfBirthDate"
                                           name="birth-date"
                                           v-model="birthday.date"
                                           :options="days"
                                           v-validate="'required'"
                                           class="form-input"
                                           :class="{'is-danger': errors.first('register-form.birth-date')}"/>
                        </b-form-group>
                    </div>
                    <div class="flex-fill mx-2">
                        <b-form-group label-for="registerMonthOfBirthDate"
                                      :state="!errors.first('register-form.birth-month')"
                                      :invalid-feedback="errors.first('register-form.birth-month')">
                            <b-form-select id="registerMonthOfBirthDate"
                                           name="birth-month"
                                           v-model="birthday.month"
                                           :options="months"
                                           v-validate="'required'"
                                           class="form-input"
                                           :class="{'is-danger': errors.first('register-form.birth-month')}"/>
                        </b-form-group>
                    </div>
                    <div class="flex-fill ml-2">
                        <b-form-group label-for="registerYearOfBirthDate"
                                      :state="!errors.first('register-form.birth-year')"
                                      :invalid-feedback="errors.first('register-form.birth-year')">
                            <b-form-select id="registerYearOfBirthDate"
                                           name="birth-year"
                                           v-model="birthday.year"
                                           :options="years"
                                           v-validate="'required'"
                                           class="form-input"
                                           :class="{'is-danger': errors.first('register-form.birth-year')}"/>
                        </b-form-group>
                    </div>
                </div>

                <b-form-group label-for="registerAcceptance" state="!errors.first('register-form.acceptance')"
                              :invalid-feedback="errors.first('register-form.acceptance')">
                    <b-form-checkbox id="registerAcceptance"
                                     v-model="form.acceptance"
                                     v-validate="'required'"
                                     class="form-input orangogo-checkbox"
                                     :class="{'is-danger': errors.first('register-form.acceptance')}"
                    >
                        Accetto i <a href="/terms" target="_blank">termini di servizio</a> e la <a href="/privacy" target="_blank">privacy policy</a>
                    </b-form-checkbox>
                </b-form-group>

                <b-btn class="orange-gradient-button text-capitalize" variant="default" block type="submit">Registrati
                </b-btn>
            </b-form>
            <div class="modal-user-register__divider pt-3 pb-2"></div>
            <p class="modal-user-register__register">Hai già un account? <a href="#"
                                                                            @click="$store.dispatch('onShowLogin')">Effettua
                il login</a></p>
            <p class="modal-user-register__register">Sei una società sportiva? <a href="https://landing.orangogo.it/insertsocmain/">Registrala da
                qui</a></p>
        </div>
    </b-modal>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Spinner from 'vue-simple-spinner';

    export default {
        name: 'UserRegister',
        components: {
            Spinner
        },
        data() {
            return {
                form: {},
                days: [
                    {value: undefined, text: 'Giorno'}
                ],
                months: [
                    {value: undefined, text: 'Mese'},
                    {value: 0, text: 'Gennaio'},
                    {value: 1, text: 'Febbraio'},
                    {value: 2, text: 'Marzo'},
                    {value: 3, text: 'Aprile'},
                    {value: 4, text: 'Maggio'},
                    {value: 5, text: 'Giugno'},
                    {value: 6, text: 'Luglio'},
                    {value: 7, text: 'Agosto'},
                    {value: 8, text: 'Settembre'},
                    {value: 9, text: 'Ottobre'},
                    {value: 10, text: 'Novembre'},
                    {value: 11, text: 'Dicembre'}
                ],
                years: [
                    {value: undefined, text: 'Anno'}
                ],
                birthday: {},
                loading: false
            }
        },
        created() {
            for (let i = 1; i <= 31; i++) {
                this.days.push({value: i, text: i});
            }
            for (let i = new Date().getFullYear(); i >= 1970; i--) {
                this.years.push({value: i, text: i});
            }
        },
        computed: {
            ...mapGetters({
                redirect: 'loginRedirect',
                googleUrl: 'googleLogin',
                facebookUrl: 'facebookLogin'
            }),
            isVisible: {
                set(boolean) {
                    this.$store.commit('SET_REGISTER_SHOW', boolean)
                },
                get() {
                    return this.$store.state.globalModals.register.show;
                }
            }
        },
        methods: {
            doRegister() {
                this.$validator.validate().then(result => {
                    if (result) {
                        if (this.form.acceptance != true) {
                            $('#registerAcceptance').parent().addClass('border-danger-checkbox');
                            return;
                        }
                        const birthday = new moment().year(this.birthday.year).month(this.birthday.month);
                        if (birthday.daysInMonth() >= this.birthday.date) {
                            this.form.birthday = birthday.date(this.birthday.date).format('DD/MM/YYYY');
                        } else {
                            window.alerts.error('Data non valida!');
                            return;
                        }
                        this.loading = true;
                        axios({
                            method: 'post',
                            url: '/auth/register',
                            data: this.form
                        }).then((response) => {
                            this.$store.dispatch('loginCallback').then((response) => {
                                this.loading = false;
                                window.alerts.success('Registrazione Completata');
                                if (this.redirect)
                                    window.location.href = this.redirect;
                                else
                                    location.reload();
                            });
                        }).catch((err) => {
                            this.loading = false;
                            this.handleError(err.response);
                        });
                    }
                });
            }
        }
    }
</script>
