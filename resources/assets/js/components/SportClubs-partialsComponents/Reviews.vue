<template>
    <div class="col-12 info-box text-center mt-5 pt-5 hv--100">
        <div class="container ml-5">
            <div class="info-box--text float-left">
                <h4 class="font-weight-bold">Who has already chosen us</h4>
            </div>
            <div class="info-box--buttons float-right position-absolute" style="right: 4.5rem!important">
                <button class="btn d-inline-block swiper-button-prev" slot="button-prev"><i class="fas fa-arrow-left"></i></button>
                <button class="btn d-inline-block swiper-button-next" slot="button-next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="info-box--slider__box w-100">
            <swiper :options="swiperOption" ref="mySwiper">
                <!-- slides -->
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="card">
                        <div class="card-body">
                            <img class="card-img-top" src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="Card image cap">
                            <div class="card-body--tittle-box text-left">
                                <h5 class="card-title">Jaclyn Lee</h5>
                                <p class="text-muted">@JJLee</p>
                            </div>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <p class="float-left text-primary">12:44 - JAN, 28 2018</p>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div>


</template>

<script>
    import 'swiper/dist/css/swiper.css'

    import { swiper, swiperSlide } from 'vue-awesome-swiper'

    export default {
        components: {
            'swiper': swiper,
            'swiper-slide': swiperSlide
        },
        name: 'Reviews',
        data() {
            return {
                swiperOption: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    }
                }
            }
        },
        mounted () {},
        methods: {
            slideNext() {
                this.$refs.slider.$emit('slideNext')
            },
            slidePre() {
                this.$refs.slider.$emit('slidePre')
            }
        }
    }
</script>

<style scoped>
    .swiper-button-prev, .swiper-button-next {
        position: initial;
    }

    .btn:focus, .btn.focus {
        outline: 0;
        -webkit-box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0);
    }

</style>