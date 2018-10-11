<template>
    <div id="element-spinner-modal">
        <div class="v-element-spinner" v-show="loading">
            <div class="v-element-clip">
                <div class="v-clip-rotation" v-bind:style="spinnerStyle"></div>
                <h2>{{text}}</h2>
            </div>

        </div>
    </div>
</template>

<script>
    export default {

        name: 'ElementClipLoader',

        props: {
            text: '',
            loadingprop: {
                type: Boolean,
                default: true
            },
            color: {
                type: String,
                default: '#5dc596'
            },
            size: {
                type: String,
                default: '35px'
            },
            radius: {
                type: String,
                default: '100%'
            },
            borderWidth: {
                type: String,
                default: '10px'
            },
        },
        data() {
            return {
                loading: false
            }
        },
        created() {
            var self = this;

            this.loading = this.loadingprop

            this.$bus.$on('searching', function () {
                self.loading = true;
            });

            this.$bus.$on('filtered', function () {
                self.loading = false;
            });
        },
        computed: {
            spinnerStyle () {
                return {
                    height: this.size,
                    width: this.size,
                    borderWidth: this.borderWidth,
                    borderStyle: 'solid',
                    borderColor: this.color + ' ' + this.color + ' transparent',
                    borderRadius: this.radius,
                    background: 'transparent'
                }
            }
        }
    }
</script>

<style>
    .v-element-spinner
    {
        /*	  font-size: 10px;

            width: 60px;
            height: 40px;*/
        /*margin: 25px auto;*/
        text-align: center;

    }

    .v-element-spinner .v-clip-rotation
    {
        -webkit-animation: v-clipDelay 0.75s 0s infinite linear;
        animation: v-clipDelay 0.75s 0s infinite linear;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;

        display: inline-block;
    }

    @-webkit-keyframes v-clipDelay
    {
        0%
        {
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%
        {
            -webkit-transform: rotate(180deg) scale(0.8);
            transform: rotate(180deg) scale(0.8);
        }
        100%
        {
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }

    @keyframes v-clipDelay
    {
        0%
        {
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%
        {
            -webkit-transform: rotate(180deg) scale(0.8);
            transform: rotate(180deg) scale(0.8);
        }
        100%
        {
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
</style>
