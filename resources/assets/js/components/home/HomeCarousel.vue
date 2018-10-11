<template>
    <div class="home-carousel">
        <div class="home-carousel__directions">
            <a class="home-carousel__directions__btn home-carousel__directions__btn--prev" href="javascript:void(0)"
               @click="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="home-carousel__directions__btn home-carousel__directions__btn--next" href="javascript:void(0)"
               @click="next">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
        <div class="home-carousel__content"
             :style="{'width': 'calc(' + 100 * items.length / count + '% + '+ 20 * items.length / count + 'px)', 'marginLeft': 'calc(-' + 100 * active / count + '% - ' + 20 * active / count + 'px)'}">
            <div class="home-carousel__content__item" v-for="(item, index) in items" :key="index"
                 :style="{'width': 100 / items.length + '%'}">
                <home-course-card :course="item"></home-course-card>
            </div>
        </div>
    </div>
</template>

<script>
    import HomeCourseCard from '../home/HomeCourseCard';

    export default {
        name: 'homecarousel',
        components: {
            HomeCourseCard,
        },
        props: {
            items: {
                type: Array,
                required: true
            },
            countProp: {
                type: Number,
                default: 1
            }
        },
        data() {
            return {
                active: 0,
                count: 4
            }
        },
        mounted() {
            let self = this;
            this.resizeCount();

            window.addEventListener('resize', function () {
                self.resizeCount();
            });
        },
        methods: {
            resizeCount() {
                if (window.innerWidth <= 992)
                    this.count = 3;
                if (window.innerWidth <= 768)
                    this.count = 2;
            },
            prev() {
                if (this.items.length <= this.count) return;
                if (this.active === 0) {
                    this.active = this.items.length - this.count;
                } else {
                    this.active--;
                }
            },
            next() {
                if (this.items.length <= this.count) return;
                if (this.active === this.items.length - this.count) {
                    this.active = 0;
                } else {
                    this.active++;
                }
            }
        }
    }
</script>
