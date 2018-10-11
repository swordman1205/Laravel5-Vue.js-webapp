<template>
    <b-modal v-model="isVisible" hide-footer title="Accedi a Orangogo">
        <div class="modal-user-login">
            <div class="modal-user-login__mask" v-if="loading">
                <spinner message="Logging In..."></spinner>
            </div>
            <b-form @submit.prevent="doLogin" data-vv-scope="login-form">
                <b-form-group label-for="loginEmail"
                              :state="!errors.first('login-form.email')"
                              :invalid-feedback="errors.first('login-form.email')">
                    <b-form-input id="loginEmail"
                                  name="email"
                                  type="email"
                                  v-validate="'required|email'"
                                  v-model="form.email"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('login-form.email')}"
                                  placeholder="Indirizzo email">
                    </b-form-input>
                </b-form-group>
                <b-form-group label-for="loginPassword"
                              :state="!errors.first('login-form.password')"
                              :invalid-feedback="errors.first('login-form.password')">
                    <b-form-input id="loginPassword"
                                  name="password"
                                  type="password"
                                  v-validate="'required'"
                                  v-model="form.password"
                                  class="form-input"
                                  :class="{'is-danger': errors.first('login-form.password')}"
                                  placeholder="Password">
                    </b-form-input>
                </b-form-group>
                <!--<b-form-group label-for="loginRemember">
                    <b-form-checkbox id="loginRemember"
                                     v-model="form.remember"
                                     class="form-input orangogo-checkbox">
                        Ricordami
                    </b-form-checkbox>
                </b-form-group>-->
                <b-btn class="orange-solid-button text-capitalize" variant="default" block type="submit">Accedi</b-btn>
            </b-form>
            <a class="modal-user-login__forgot-password" href="/password/reset">Password dimenticata?</a>
            <div class="modal-user-login__divider">
                <span>oppure</span>
            </div>
            <div class="modal-user-login__social-buttons">
                <GoogleButton></GoogleButton>
                <FacebookButton></FacebookButton>
            </div>
            <div class="modal-user-login__divider modal-user-login__divider--no-text m-t-50"></div>
            <p class="modal-user-login__register text-center">Non hai un account? <a href="#"
                                                                               @click="$store.dispatch('onShowRegister')">Registrati</a>
            </p>
        </div>
    </b-modal>
</template>

<script>
    import {mapGetters} from 'vuex';
    import FacebookButton from '../buttons/FacebookButton'
    import GoogleButton from '../buttons/GoogleButton'
    import Spinner from 'vue-simple-spinner';

    export default {
        name: 'UserLogin',
        components: {
            FacebookButton,
            GoogleButton,
            Spinner
        },
        data() {
            return {
                form: {},
                loading: false
            }
        },
        computed: {
            ...mapGetters({
                redirect: 'loginRedirect'
            }),
            isVisible: {
                set(boolean) {
                    this.$store.commit('SET_LOGIN_SHOW', boolean)
                },
                get() {
                    return this.$store.state.globalModals.login.show;
                }
            }
        },
        methods: {
            doLogin() {
                this.$validator.validate().then(result => {
                    if (result) {
                        this.loading = true;
                        axios({
                            method: 'post',
                            url: '/auth/login',
                            data: this.form
                        }).then((response) => {
                            this.$store.dispatch('loginCallback').then((response) => {
                                this.loading = false;
                                window.alerts.success('Accesso effettuato correttamente');
                                if (this.redirect)
                                    window.location.href = this.redirect;
                            });
                        }).catch((err) => {
                            this.loading = false;
                            this.handleError(err.response);
                        });
                    }
                })
            }
        }
    }
</script>
