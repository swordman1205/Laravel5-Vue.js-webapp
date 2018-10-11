<template>
    <div class="container-fluid d-flex work-container" id="howworks">
        <div class="row my-auto">
            <div class="offset-1 col-md-3 d-flex" >
                <div class="work-container--texts my-auto">
                    <h3 class="font-weight-bold mb-4">Come funziona Orangogo?</h3>
                    <p class="mb-4">Ci sono molte persone nel mondo con talenti meravigliosi che sfruttano solo una piccola percentuale del loro potenziale.</p>
                    <div class="work-container--texts__buttons">
                        <button class="btn"  @click="slidePre()"><i class="fas fa-arrow-left"></i></button>
                        <button class="btn" @click="slideNext()"><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-8 work-container--slider">
                <div class="work-container--slider__box">
                    <!-- Configuring slider components -->
                    <slider ref="slider" :pages="pages" :sliderinit="sliderinit" style="white-space: normal;">
                        <!-- Set loading -->
                        <div slot="loading">loading...</div>
                    </slider>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import slider from 'vue-concise-slider'

    export default {
        name: "howOrangogoWork",
        data () {
            return {
                //Image list
                pages:[
                    {
                        html: '<div class="container">' +
                        '   <div class="row">' +
                        '       <div class="col-6 float-left">' +
                        '           <img class="slider-item--img" src="img/slider-img--2.png" alt="slider-img">' +
                        '       </div>' +
                        '       <div class="col-6 text-left float-right my-auto mx-0">' +
                        '           <h3 class="slider-item--tittle">Aggiungi la tua Società sportiva</h3>' +
                        '           <p class="slider-item--text">In pochi semplici step, crea la tua pagina sul Motore di ricerca degli sport.</p>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>',
                        style: {
                            'background': '#F47E2E'
                        }
                    },
                    {
                        html: '<div class="container">' +
                        '   <div class="row">' +
                        '       <div class="col-6 float-left">' +
                        '           <img class="slider-item--img" src="img/slider-img--1.png" alt="slider-img">' +
                        '       </div>' +
                        '       <div class="col-6 text-left float-right my-auto mx-0">' +
                        '           <h3 class="slider-item--tittle">Ricevi le richieste via email</h3>' +
                        '           <p class="slider-item--text">Ricevi le richieste di famiglie e sportivi che cercano lo sport online.</p>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>',
                        style: {
                            'background': '#4bbfc3'
                        }
                    },
                    {
                        html: '<div class="container">' +
                        '   <div class="row">' +
                        '       <div class="col-6 float-left">' +
                        '           <img class="slider-item--img" src="img/slider-img--1.png" alt="slider-img">' +
                        '       </div>' +
                        '       <div class="col-6 text-left float-right my-auto mx-0">' +
                        '           <h3 class="slider-item--tittle">Gestisci comodamente le richieste.</h3>' +
                        '           <p class="slider-item--text">Dal tuo profilo personale potrai vedere e gestire tutte le richieste che ricevi su OrangoGo.</p>' +
                        '       </div>' +
                        '   </div>' +
                        '</div>',
                        style: {
                            'background': '#7baabe'
                        }
                    }
                ],
                //Sliding configuration [obj]
                sliderinit: {
                    currentPage: 0,
                    thresholdDistance: 50,
                    thresholdTime: 300,
                    loop:false,
                    infinite:1,
                    slidesToScroll:1,
                    direction:'horizontal',
                    autoplay:0
                }
            }
        },
        components: {
            slider
        },
        methods: {
            slideNext () {
                this.$refs.slider.$emit('slideNext')
            },
            slidePre () {
                this.$refs.slider.$emit('slidePre')
            }
        }
    }
</script>

<style scoped>

</style>