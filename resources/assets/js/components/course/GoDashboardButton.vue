<template>
    <a @click="goToDashboard()" class="btn registration-navbar--button my-2 my-sm-0 pl-4 pr-4">Vai al profilo</a>
</template>

<script>
    export default {
        props: ['companyprop', 'courseprop', 'action'],
        data() {
            return {
                company: null,
                course: null
            }
        },
        created() {
            var self = this;

            this.$bus.$on('course-created', function (course) {
                self.course = course;
                self.course.is_complete = 0;
            });
        },
        mounted() {
            this.company = this.companyprop;
            this.course = this.courseprop;
        },
        methods: {
            goToDashboard() {
                var self = this;

                if (this.course == null) {
                    window.location.replace("/societa_sportive/" + self.company.slug + "/dashboard");
                } else if (self.course.is_complete == 0) {
                    bootbox.confirm({
                        message: "Il corso non è ancora completo, desideri salvare il corso?",
                        buttons: {
                            confirm: {
                                label: 'Salva',
                                className: 'orange-gradient-button'
                            },
                            cancel: {
                                label: 'No',
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                window.location.replace("/societa_sportive/" + self.company.slug + "/dashboard");
                            } else {
                                if (this.action == 'create') {
                                    axios({
                                        method: 'delete',
                                        url: '/courses/' + self.course.id
                                    }).then((response) => {
                                        window.location.replace(response.data.redirect);
                                    }).catch((error) => {
                                        self.$emit('init-error', 'Delete failed');
                                    });
                                }
                            }
                        }
                    });
                } else {
                    window.location.replace("/societa_sportive/" + self.company.slug + "/dashboard");
                }
            }
        }
    }
</script>