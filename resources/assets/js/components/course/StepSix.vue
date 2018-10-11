<script>
    import Datepicker from 'vuejs-datepicker';
    import Timepicker from './Timepicker'

    export default {
        components: {
            'datepicker': Datepicker,
            'timepicker': Timepicker
        },
        name: "StepSix",
        props: {
            'course': Object,
            'lesson': Object
        },
        data() {
            return {
                loading: false,
                lessonDurationOptions: [
                    {
                        text: 'Durante tutte le ore di lezione, su prenotazione',
                        value: true
                    },
                    {
                        text: 'Solo in una data/ora specifica (es. il giorno delle lezioni aperte)',
                        value: false
                    }
                ],
                lessonPriceOptions: [
                    {
                        text: 'Prima lezione gratuita',
                        value: true
                    },
                    {
                        text: 'Prima lezione €',
                        value: false
                    }
                ],
                validationErrors: {
                    dateTimeError: false,
                    priceError: false,
                    equipmentError: false
                },
            }
        },
        mounted() {
            var self = this;

            setTimeout(function () {
                $('#datePickerForStepSix').val(moment(self.lesson.specificDate.date).format('DD/MM/YYYY'))
            }, 500)
        },
        methods: {
            previousStep() {
                this.$emit('update-step', 5, 6);
            },
            isLoading() {
                return this.loading;
            },
            postForm() {
                if (this.validation()) {
                    this.loading = true;
                    this.$bus.$emit('searching');

                    axios({
                        method: 'put',
                        url: '/courses/' + this.course.courseId + '/lesson',
                        data: {
                            lesson: this.lesson
                        },
                    }).then((response) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.$emit('update-step', 7, 6);
                    }).catch((error) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.message = 'Error';
                    });
                }
            },
            validation() {
                this.validationErrors.dateTimeError = false;
                this.validationErrors.priceError = false;
                this.validationErrors.equipmentError = false;

                if (!this.lesson.isDuringCourseTime) {
                    if (!this.lesson.specificDate.date || this.lesson.specificDate.time.hours < 0 || this.lesson.specificDate.time.hours > 23 || this.lesson.specificDate.time.minutes < 0 || this.lesson.specificDate.time.minutes > 59) {
                        this.validationErrors.dateTimeError = true;
                        return false;
                    }
                }
                if (!this.lesson.isFreeLesson && !this.lesson.price) {
                    this.validationErrors.priceError = true;
                    return false;
                }

                if (this.lesson.hasEquipments && !this.lesson.equipments) {
                    this.validationErrors.equipmentError = true;
                    return false;
                }
                return true;
            },
            dateFormatter(date) {
                return moment(date).format('DD/MM/YYYY');
            }
        }
    }
</script>
<style scoped>

</style>
