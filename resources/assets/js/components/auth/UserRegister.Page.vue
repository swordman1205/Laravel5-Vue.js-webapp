<script>
    import Spinner from 'vue-simple-spinner';

    const moment = require('moment');

    export default {
        name: 'UserPageRegister',
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
        methods: {
            doRegister() {
                this.$validator.validate().then(result => {
                    if (result) {
                        const birthday = new moment().year(this.birthday.year).month(this.birthday.month);
                        if (birthday.daysInMonth() >= this.birthday.date) {
                            this.form.birthday = birthday.date(this.birthday.date).format('DD/MM/YYYY');
                        } else {
                            window.alerts.error('You selected an invalid date!');
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
