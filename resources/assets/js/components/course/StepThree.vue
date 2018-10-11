<script>

    export default {
        name: "stepThree",
        props: {
            'course': Object,
            'audience': Object
        },
        data() {
            return {
                loading: false,
                disabilities: [],
                message: null,
                levels: ['Principiante', 'Intermedio', 'Esperto', 'Professionista'],
            }
        },
        mounted() {
            axios({
                method: 'get',
                url: '/disabilities'
            }).then((response) => {
                this.disabilities = response.data.disabilities;
            }).catch((error) => {
                this.message = 'Error';
            });
        },
        computed: {
            ageValidation() {
                return parseInt(this.course.startAge) >= 0 && parseInt(this.course.startAge) < parseInt(this.course.endAge);
            }
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            selectLevel(level) {
                this.course.selectedLevel = level;
            },
            previousStep() {
                this.$emit('update-step', 2);
            },
            postForm() {
                var self = this;

                this.validationError = {
                    name: false,
                    email: false
                };

                this.loading = true;
                this.$bus.$emit('searching');

                let formData = new FormData()
                formData.append('startAge', this.course.startAge);
                formData.append('endAge', this.course.endAge);
                formData.append('level', this.course.selectedLevel);
                formData.append('audience[forAbleBodied]', this.audience.forAbleBodied);
                formData.append('audience[forDisabled]', this.audience.forDisabled);
                formData.append('audience[checkedDisabilities]', this.audience.checkedDisabilities);
                formData.append('gender[male]', this.course.is_for_male);
                formData.append('gender[female]', this.course.is_for_female);

                axios({
                    method: 'post',
                    url: '/courses/' + this.course.courseId + '/responsible',
                    data: formData,
                }).then((response) => {
                    this.$emit('update-step', 4);
                    this.$emit('update-course-id', response.data.course.id);
                    this.loading = false;
                }).catch((error) => {
                    this.$emit('update-course-id', response.data.course.id);
                    this.loading = false;
                    this.message = 'Error';
                });
            }
        },
        watch: {
            'course.startAge': _.debounce(function () {
                if (this.course.endAge) {
                    if (parseInt(this.course.endAge) <= parseInt(this.course.startAge)) {
                        this.course.endAge = parseInt(this.course.startAge) + 1
                    }
                }
            }, 1000),
            'course.endAge': _.debounce(function () {
                if (this.course.startAge) {
                    if (parseInt(this.course.startAge) >= parseInt(this.course.endAge)) {
                        this.course.startAge = parseInt(this.course.endAge) - 1;
                    }
                }
            }, 1000),
        },
    }
</script>
<style scoped>
</style>