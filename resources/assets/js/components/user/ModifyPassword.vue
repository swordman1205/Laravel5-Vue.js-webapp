<script>
    export default {
        name: "ModifyPassword",
        props:{
            'user-id': String
        },
        data() {
            return {
                currentPassword: null,
                newPassword: null,
                validationErrors: {
                    currentPassword: null,
                    newPassword: null
                }
            }
        },
        methods: {
            postForm() {
                axios({
                    method: 'put',
                    url: '/users/' + this.userId + '/update-password',
                    data: {
                        currentPassword: this.currentPassword,
                        newPassword: this.newPassword
                    }
                }).then((response) => {
                        this.$emit('reset-modify');
                        this.$emit('init-success', 'Password Salvata');
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