<script>
    var blockPage = function () {
        //App.blockUI({target: ".app"});
    };

    export default {
        props: ['sportmenprop'],
        data() {
            return {
                total: 0,
                selected: 'first',
                options: [
                    { text: 'First radio', value: 'first' },
                    { text: 'Second radio', value: 'second' },
                ]
            }
        },
        mounted() {

        },
        methods: {
            triggerSpinner() {
                $('#spinner-modal').modal();
            },

        },
    }
</script>