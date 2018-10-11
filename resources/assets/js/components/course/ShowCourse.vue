<script>
    import HomeCarousel from '../home/HomeCarousel';
    import HomeCourseCard from '../home/HomeCourseCard';
    import axios from 'axios'
    import * as VueGoogleMaps from 'vue2-google-maps';

    export default {
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            'google-map': VueGoogleMaps.Map,
            'google-marker': VueGoogleMaps.Marker,
            HomeCarousel,
            HomeCourseCard,
        },
        name: "showCourse",
        props: {
            coursesprop: null
        },
        data() {
            return {
                courses: []
            }
        },
        mounted() {
            this.courses = this.coursesprop;
            this.$refs.mapRef.$mapPromise.then((map) => {
                map.set('navigationControl' , false);
                map.set('mapTypeControl' , false);
            })
        },
        computed: {
            courseList() {
                if (!this.courses) return [];
                return this.courses;
            }
        },
        methods: {
        }
    }
</script>