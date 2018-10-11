<template>
    <div class="main-panel-picker">
        <input class="form-control form-input timepicker__input registrations-steps--box__input-field-short registrations-steps--box__input-day-hour"
               type="text" @click="togglePicker" :value="formatTime()" @change="parseTime()"
               :class="{'is-danger': (this.validationErrors.hours || this.validationErrors.minutes)}">
        <div v-if="isShowPicker">
            <div class="timepicker noselect" :class="'timepicker-'+_uid">
                <div class="timepicker__content">
                    <div class="timepicker__topline">
                        <div class="timepicker__column" @mousedown="longMouseDownStart(addHour)"
                             @mouseup="longMouseDownStop">
                            <i class="arrow up"></i>
                        </div>
                        <div class="timepicker__column" @mousedown="longMouseDownStart(addMinute)"
                             @mouseup="longMouseDownStop">
                            <i class="arrow up"></i>
                        </div>
                    </div>
                    <div class="timepicker__middleline">
                        <input class="timepicker__column" :class="{'is-danger': this.validationErrors.hours}"
                               v-model="value.hours" type="number" min="0" step="1"
                               max="23"/>
                        <span class="timepicker__separator">:</span>
                        <input class="timepicker__column" :class="{'is-danger': this.validationErrors.minutes}"
                               v-model="value.minutes" type="number" min="0" step="5"
                               max="59"/>
                    </div>
                    <div class="timepicker_downline">
                        <div class="timepicker__column" @mousedown="longMouseDownStart(subHour)"
                             @mouseup="longMouseDownStop">
                            <i class="arrow down"></i>
                        </div>
                        <div class="timepicker__column" @mousedown="longMouseDownStart(subMinute)"
                             @mouseup="longMouseDownStop">
                            <i class="arrow down"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>


<script>
    const defaultOptions = {
        headerShow: true,
        headerText: 'Friday Time Picker',
    };

    export default {
        props: ['value'],
        mounted() {
            document.onclick = (e) => {
                var path = e.path || (e.composedPath && e.composedPath());
                if (path) {
                    if (path[0].querySelector('.timepicker-' + this._uid)) {
                        this.togglePicker();
                    }
                } else {
                    // This browser doesn't supply path information
                }

            };
        },
        data() {
            return {
                isShowPicker: false,
                buildedTime : '00:00',
                validationErrors: {
                    hours: null,
                    minutes: null
                }
            }
        },
        watch: {
            'value.hours'() {
                this.validationErrors.hours = null;
                if (this.value.hours > 23 || this.value.hours < 0) {
                    this.validationErrors.hours = 'Invalid hours';
                }
            },
            'value.minutes'() {
                this.validationErrors.minutes = null;
                if (this.value.minutes > 59 || this.value.minutes < 0) {
                    this.validationErrors.minutes = 'Invalid minutes';
                }
            }
        },
        methods: {
            togglePicker() {
                this.isShowPicker = !this.isShowPicker;
            },
            longMouseDownStart(method) {
                method();
                this.interval = setInterval(() => {
                    method();
                }, 200)
            },
            longMouseDownStop() {
                clearInterval(this.interval);
            },
            addHour() {
                this.value.hours = parseInt(this.value.hours);
                if (this.value.hours < 23 && this.value.hours >= 0) {
                    this.value.hours += 1;
                }
                else {
                    this.value.hours = 0;
                }
            },
            subHour() {
                this.value.hours = parseInt(this.value.hours);
                if (this.value.hours > 0 && this.value.hours < 23) {
                    this.value.hours -= 1;
                }
                else {
                    this.value.hours = 23;
                }
            },
            addMinute() {
                this.value.minutes = parseInt(this.value.minutes);
                this.value.minutes = Math.ceil(this.value.minutes / 5) * 5;
                if (this.value.minutes < 55 && this.value.minutes >= 0)
                    this.value.minutes += 5;
                else {
                    this.value.minutes = 0;
                    this.addHour();
                }
            },
            subMinute() {
                this.value.minutes = parseInt(this.value.minutes);
                this.value.minutes = Math.ceil(this.value.minutes / 5) * 5;
                if (this.value.minutes > 0 && this.value.minutes < 60) {
                    this.value.minutes -= 5;
                }
                else {
                    this.value.minutes = 55;
                    this.subHour();
                }
            },
            formatNumber(number) {
                if (number < 10)
                    return "0" + number;
                return number;
            },
            formatTime() {
                this.buildedTime = this.formatNumber(this.value.hours) + ":" + this.formatNumber(this.value.minutes);
                return this.buildedTime;
            },
            parseTime() {

            }
        },
    }
</script>

<style lang="scss">

    // Hide number arrows in input
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    $blue: #17BEBB;
    $danger: #EC202C;

    .is-danger {
        border: 1px solid $danger !important;
    }

    .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently
                                         supported by Chrome and Opera */
    }

    .main-panel-picker {
        border: none !important;
    }

    .timepicker {
        box-shadow: 0 0 0 1px rgba(14, 41, 57, 0.12), 0 2px 5px rgba(14, 41, 57, 0.44), inset 0 -1px 2px rgba(14, 41, 57, 0.15);
        background: rgb(254, 254, 254);
        border-radius: 0.1px;
        width: 90%;
        height: 100px;
        font-size: 16px;
        font-family: monospace;
        position: absolute;
        z-index: 1;
        &__input {
            width: 90% !important;
            cursor: pointer;
            text-align: center;
        }
        &__input[readonly] {
            background-color: white;
        }
        &__content {
            text-align: center;
            padding: 10px;
            position: relative;

            i {
                border: solid $blue;
                border-width: 0 3px 3px 0;
                display: inline-block;
                padding: 5px;
                cursor: pointer;
            }

            .right {
                transform: rotate(-45deg);
                -webkit-transform: rotate(-45deg);
            }

            .left {
                transform: rotate(135deg);
                -webkit-transform: rotate(135deg);
            }

            .up {
                transform: rotate(-135deg);
                -webkit-transform: rotate(-135deg);
            }

            .down {
                transform: rotate(45deg);
                -webkit-transform: rotate(45deg);
            }
        }
        &__column {
            width: 28%;
            display: inline-block;
            padding: 0px;
            height: 2rem;
            text-align: center;
        }
        &__separator {
            position: absolute;
            left: 48.8%;
            top: 31px;
            font-size: 22px;
            font-family: sans-serif;
        }
        &__middleline {
            margin-top: 0px;
            margin-bottom: 0px;
            font-size: 20px;
        }
        &__topline {
            height: 24px;
        }
    }

</style>