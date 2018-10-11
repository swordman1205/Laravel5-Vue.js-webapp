<script>
    export default {
        name: "StepSeven",
        props: {
            'course': Object,
            'offer': Object,
            'company': Object,
        },
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            previousStep() {
                this.$emit('update-step', 6);
            },
            postForm() {
                this.loading = true;
                this.$bus.$emit('searching');

                axios({
                    method: 'put',
                    url: '/courses/' + this.course.courseId + '/offer',
                    data: {
                        offer: this.offer
                    },
                }).then((response) => {
                    this.loading = false;
                    this.$bus.$emit('filtered');

                    if (this.offer.hasLessons) {
                        this.$emit('update-step', 8, 7);
                        return;
                    }

                    if (this.offer.hasSubscription) {
                        this.$emit('update-step', 9, 7);
                        return;
                    }

                    window.location = '/societa_sportive/' + this.company.slug +'/dashboard'
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
