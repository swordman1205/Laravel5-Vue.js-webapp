<script>
    import HomeCarousel from '../home/HomeCarousel'
    import PhotoGallery from './PhotoGallery'
    import HomeCourseCard from '../home/HomeCourseCard'

    export default {
        name: "Detail",
        components: {
            HomeCarousel,
            HomeCourseCard,
            PhotoGallery
        },
        props: {
            company: {
                type: Object,
                required: true
            },
            courses: {
                type: Array,
                default: []
            },
            sportsProp: {
                type: Object,
                default : {}
            },
            images: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                filters: {},
                sports : [],
                ageList: [],
                dayList: [
                    {value: '0', name: 'Lunedì'},
                    {value: '1', name: 'Martedì'},
                    {value: '2', name: 'Mercoledì'},
                    {value: '3', name: 'Giovedì'},
                    {value: '4', name: 'Venerdì'},
                    {value: '5', name: 'Sabato'},
                    {value: '6', name: 'Domenica'}
                ],
                reviews: [
                    {
                        user: {
                            name: 'Lorem Ipsum',
                            avatar: 'https://picsum.photos/600/300/?image=29'
                        },
                        rating: 4,
                        title: 'Lorem ipsum',
                        description: 'lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum'
                    },
                    {
                        user: {
                            name: 'Lorem Ipsum',
                            avatar: 'https://picsum.photos/600/300/?image=29'
                        },
                        rating: 4,
                        title: 'Lorem ipsum',
                        description: 'lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum'
                    },
                    {
                        user: {
                            name: 'Lorem Ipsum',
                            avatar: 'https://picsum.photos/600/300/?image=29'
                        },
                        rating: 4,
                        title: 'Lorem ipsum',
                        description: 'lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum'
                    }
                ],
                contactForm: {},
                hours: [
                    {day: 'Lunedì', time: '18:00 - 20:00'},
                    {day: 'Martedi', time: '18:00 - 20:00'},
                    {day: 'mercoledi', time: '18:00 - 20:00'},
                    {day: 'giovedi', time: '18:00 - 20:00'},
                    {day: 'venerdì', time: '18:00 - 20:00'},
                    {day: 'sabato', time: '18:00 - 20:00'},
                    {day: 'DOMENICA', time: null},
                ],
                limitDesc: 200,
                descExpanded: false
            }
        },
        mounted() {
            let self = this;

            for (var key in this.sportsProp) {
                this.sports.push(this.sportsProp[key]);
            }

            this.$refs.mapRef.$mapPromise.then((map) => {
                map.set('navigationControl', false);
                map.set('mapTypeControl', false);
            })
        },
        created() {
            for (let i = 10; i <= 20; i++) {
                this.ageList.push(i)
            }
        },
        methods: {
            loadMoreDesc() {
                this.descExpanded = !this.descExpanded
            }
        },
        computed: {
            calcDescription() {
                if (this.descExpanded)
                    return this.company.description;
                return `${this.company.description.slice(0, this.limitDesc)}...`;
            },
            isLoadMore() {
                return this.company.description.length > this.limitDesc
            }
        }
    }
</script>
