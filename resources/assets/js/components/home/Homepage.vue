<script>
    import HomeSportSearch from './HomeSportSearch';
    import HomeCarousel from './HomeCarousel';
    import HomeCourseCard from './HomeCourseCard';
    import HowOrangoWorksBreadcrumb from './HowOrangoWorksBreadcrumb';

    export default {
        name: "Homepage",
        components: {
            HomeSportSearch,
            HomeCarousel,
            HomeCourseCard,
            HowOrangoWorksBreadcrumb
        },
        props: {
            courses: {}
        },
        data() {
            return {
                sports: [
                    {url: 'https://picsum.photos/600/300/?image=25'},
                    {url: 'https://picsum.photos/600/300/?image=26'},
                    {url: 'https://picsum.photos/600/300/?image=27'},
                    {url: 'https://picsum.photos/600/300/?image=28'},
                    {url: 'https://picsum.photos/600/300/?image=29'}
                ],
                courses_by_distance: []
            }
        },
        created() {
            var self = this;

            this.$bus.$on('geolocalizated', function (args) {
                self.triggerSpinner();

                axios({
                    method: 'get',
                    url: '/courses_by_distance?latitude=' +
                    args.latitude +
                    '&longitude=' +
                    args.longitude,
                }).then((response) => {
                    self.courses_by_distance = response.data;
                    if (self.courses_by_distance.length)
                        $('#close_courses').removeClass('hidden');

                    self.shutdownSpinner();
                }).catch((error) => {
                    this.message = 'Error';
                });
            })
        },
        methods: {
            triggerSpinner() {
                $('#spinner-modal').modal();
            },
            shutdownSpinner() {
                $('#spinner-modal').modal('hide');
            },
        },
        computed: {
            courseList() {
                if (!this.courses) return [];
                return this.courses;
            },
            orderedCourseList() {
                if (!this.courses_by_distance) return [];
                return this.courses_by_distance;
            }
        }
    }
</script>
