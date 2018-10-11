<script>
    export default {
        name: "UserAccount",
        props: {
            'userId': String,
            'companyId': String
        },
        data() {
            return {
                user: {
                    firstName: null,
                    lastName: null,
                    email: null,
                    password: null,
                    role: null,
                    birthday: null
                },
                validationErrors: {
                    firstName: false,
                    lastName: false,
                },
                message: null,
            }
        },
        mounted() {
            this.initUser();
        },
        watch: {
            'user': {
                handler: function (val, oldVal) {
                    this.message = null;
                },
                deep: true
            }
        },
        methods: {
            initUser() {
                this.message = null;
                axios({
                    method: 'get',
                    url: '/users/' + this.userId
                }).then((response) => {
                    this.user = {
                        firstName: response.data.user.first_name,
                        lastName: response.data.user.last_name,
                        email: response.data.user.email,
                        birthday: response.data.user.birthday,
                        password: '1234567890',
                        role: response.data.user.role !== null ? response.data.user.role.name : null
                    }

                }).catch((error) => {
                    this.$emit('init-error', 'Internal server error');
                });
            },
            postForm() {
                this.message = null;
                if (this.validation()) {
                    axios({
                        method: 'put',
                        url: '/users/' + this.userId,
                        data: {
                            first_name: this.user.firstName,
                            last_name: this.user.lastName,
                            birthday: this.user.birthday
                        }
                    }).then((response) => {
                            this.user.firstName = response.data.user.first_name;
                            this.user.lastName = response.data.user.last_name;
                            this.message = 'Saved'
                            this.$emit('init-success', 'Profilo salvato correttamente');
                        }
                    ).catch((error) => {
                        console.log(error.response);
                        if (error.response.status === 422) {
                            this.validationErrors = error.response.data.errors;
                            this.$emit('init-error', 'Impossibile salvare i dati');
                        }
                        else {
                            this.$emit('init-error', 'Internal server error');
                        }
                    });
                }
            },
            validation() {
                let passed = true;
                this.validationErrors = {
                    firstName: false,
                    lastName: false
                };

                if (!this.user.firstName) {
                    this.validationErrors.firstName = true;
                    passed = false;
                }
                if (!this.user.lastName) {
                    this.validationErrors.lastName = true;
                    passed = false;
                }

                if (!passed) {
                    window.scrollTo(0, 200);
                }

                return passed;
            }
            ,
            modifyEmail() {
                this.$emit('modify-email');
            }
            ,
            modifyPassword() {
                this.$emit('modify-password');
            }
        }
    }
</script>

<style scoped>

</style>