<script>
    import FileUpload from './FileUpload';

    export default {
        components: {
            'file-upload': FileUpload
        },
        name: "StepFour",
        props: {
            'course': Object,
            'responsible': Object,
            'audience': Object
        },
        data() {
            return {
                loading: false,
                disabilities: [],
                services: [],
                imageMaxSize: 3, // megabytes
                validationError: {
                    name: false,
                    email: false
                }
            }
        },
        mounted() {
            /*axios({
                method: 'get',
                url: '/disabilities'
            }).then((response) => {
                this.disabilities = response.data.disabilities;
            }).catch((error) => {
                this.message = 'Error';
            });*/
            axios({
                method: 'get',
                url: '/services'
            }).then((response) => {
                this.services = response.data.services;
            }).catch((error) => {
                this.message = 'Error';
            });
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            previousStep() {
                this.$emit('update-step', 3);
            },
            postForm() {
                var data = {};

                data['checkedServices'] = this.audience.checkedServices;

                if (this.responsible.name && this.responsible.name !== 'null') {
                    data['name'] = this.responsible.name;
                }
                else {
                    this.validationError.name = true;
                    return;
                }
                if (this.responsible.hasEmail) {
                    data['hasEmail'] = this.responsible.hasEmail;

                    if (this.responsible.email && this.responsible.email !== 'null') {
                        data['email'] = this.responsible.email;
                    }
                    else {
                        this.validationError.email = true;
                        return;
                    }
                }

                this.loading = true;
                this.$bus.$emit('searching');

                axios({
                    method: 'put',
                    url: '/courses/' + this.course.courseId + '/services',
                    data: data,
                }).then((response) => {
                    this.loading = false;
                    this.$bus.$emit('filtered');
                    this.$emit('update-step', 5);
                }).catch((error) => {
                    this.loading = false;
                    this.$bus.$emit('filtered');
                    this.message = 'Error';
                });
            }
        }
    }
</script>
<style scoped>

</style>
