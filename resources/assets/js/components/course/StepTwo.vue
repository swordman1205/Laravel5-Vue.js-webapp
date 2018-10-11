<script>
    import axios from 'axios';
    import Datepicker from 'vuejs-datepicker';
    import Timepicker from './Timepicker';
    import {it} from 'vuejs-datepicker/dist/locale'

    export default {
        components: {
            'datepicker': Datepicker,
            'timepicker': Timepicker
        },
        name: "StepTwo",
        props: {
            'course': Object,
            'dayNames': Array,
            'courseDays': Array,
        },
        data() {
            return {
                it: it,
                loading: false,
                pickerSetting: {
                    headerShow: false,
                },
                message: null,
                validationMessage: null,
                dateValidation: false
            }
        },
        computed: {
            disabledDates() {
                return {
                    to: new Date(this.course.startDate)
                };
            }
            ,
        }
        ,
        mounted() {
            var self = this;
            
            if (self.course.startDate && self.course.endDate) {
                setTimeout(function () {
                    $('#startDatePicker').val(moment(self.course.startDate).format('DD/MM/YYYY'))
                    $('#endDatePicker').val(moment(self.course.endDate).format('DD/MM/YYYY'))
                }, 500)
            }

            if (this.course.startDate && this.course.endDate) {
                this.dateValidation = true;
            }
            if (this.courseDays.length > 0) {
                this.courseDays.sort(this.compare)
            }
        }
        ,
        watch: {
            'course.startDate'() {
                if (this.course.startDate > this.course.endDate) {
                    this.course.endDate = null;
                    this.dateValidation = false;
                }
                else {
                    this.dateValidation = true;
                }
            }
            ,
            'course.endDate'() {
                if (this.course.startDate <= this.course.endDate) {
                    this.dateValidation = true;
                }
            }
            ,
        }
        ,
        methods: {
            isLoading() {
                return this.loading;
            },
            addTime(day, index) {
                let time = {
                    index: index,
                    name: day.name,
                    shortName: day.shortName,
                    validationError: false,
                    startTime: {
                        hours: 14,
                        minutes: 0
                    },
                    endTime: {
                        hours: 15,
                        minutes: 0
                    }
                }
                this.courseDays.push(time);
                this.courseDays.sort(this.compare)
            },
            removeTime(index) {
                this.courseDays.splice(index, 1);
            },
            validation() {
                this.validationMessage = null;
                if (this.course.startDate > this.course.endDate) {
                    this.validationMessage = 'Start date is later than end date';
                    return false;
                }
                this.courseDays.forEach((day) => {
                    day.validationError = false;
                    if (day.startTime.hours === null || day.endTime.minutes === null || day.startTime.hours > day.endTime.hours) {
                        day.validationError = true;
                        this.validationMessage = day.name + ' is invalid.';
                    }
                    else if (day.startTime.hours === day.endTime.hours && day.startTime.minutes >= day.endTime.minutes) {
                        day.validationError = true;
                        this.validationMessage = day.name + ' is invalid.';
                    }

                });

            }
            ,
            previousStep() {
                this.$emit('update-step', 1);
            }
            ,
            postForm() {
                this.validation();

                this.loading = true;
                this.$bus.$emit('searching');

                if (!this.validationMessage) {
                    axios({
                        method: 'put',
                        url: '/courses/' + this.course.courseId + '/dates',
                        data: {
                            courseDates: {
                                'startDate': this.course.startDate,
                                'endDate': this.course.endDate,
                            },
                            courseDays: this.courseDays
                        }
                    }).then((response) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.$emit('update-step', 3);

                    }).catch((error) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.message = 'Error';
                    });
                } else {
                    this.loading = false;
                    this.$bus.$emit('filtered');
                }
            },
            compare(a, b) {
                if (a.index < b.index)
                    return -1;
                if (a.index > b.index)
                    return 1;
                return 0;
            },
            dateFormatter(date) {
                return moment(date).format('DD/MM/YYYY');
            }
        }
    }
</script>
<style>
    .form-day-box > .custom-control-label::before {
        display: none !important;
    }

</style>