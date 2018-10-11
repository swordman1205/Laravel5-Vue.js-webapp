<!--
<script>
    export default {

        name: "StepDocuments",
        props: {
            'course': Object
        },
        data() {
            return {
                documents: [],
            }
        },
        mounted() {
            var self = this;

            axios({
                method: 'get',
                url: self.course.courseId + '/documents_by_course',
            }).then((response) => {
                this.documents = response.data.documents;
            }).catch((error) => {
                this.message = 'Error';
            });
        },
        methods: {
            previousStep() {
                this.$emit('update-step', 8);
            },
            postForm() {
                var self = this;
                axios({
                    method: 'put',
                    url: '/courses/' + self.course.courseId + '/documents',
                    data: {
                        documents : self.documents
                    },
                }).then((response) => {
                    this.$emit('update-step', 10);
                }).catch((error) => {
                    this.message = 'Error';
                });
            }
        }
    }
</script>
<style scoped>

</style>
-->
