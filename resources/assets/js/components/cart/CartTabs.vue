<script>
    export default {
        props: [],
        data() {
            return {
                tabIndex: 1
            }
        },
        created() {
            var self = this;
            this.$bus.$on('go-ahead', function (fr) {
                self.goAhead(fr);
            });
        },
        methods: {
            goAhead(fr) {
                console.log(fr);
                $('[tabindex="' + fr + '"]').removeClass('disabled');
                $('[tabindex="' + fr + '"]').tab('show');
            },
        },
    }
</script>