<script>
    export default {
        name: "ModifyEmail",
        props: {
            'user-id': String
        },
        data() {
            return {
                currentEmail: null,
                newEmail: null,
                validationErrors: {
                    currentEmail: null,
                    newEmail: null
                }
            }
        },
        methods: {
            postForm() {
                axios({
                    method: 'put',
                    url: '/users/' + this.userId + '/update-email',
                    data: {
                        currentEmail: this.currentEmail,
                        newEmail: this.newEmail
                    }
                }).then((response) => {
                        this.$emit('reset-modify');
                        this.$emit('init-success', 'Email salvata');
                    }
                ).catch((error) => {
                    if (error.response.status === 422) {
                        this.validationErrors = error.response.data.errors;
                        this.$emit('init-error', 'Impossibile salvare i dati');
                    }
                    else {
                        this.$emit('init-error', 'Internal server error');
                    }
                });

            }
        }
    }
</script>

<style scoped>

</style>