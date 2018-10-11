<script>
    import Sidebar from './Sidebar';
    import MyCourses from './MyCourses';
    import Bookings from './Bookings';
    import Profile from './Profile';
    import UserAccount from '@components/user/UserAccount';
    import ModifyEmail from '@components/user/ModifyEmail';
    import ModifyPassword from '@components/user/ModifyPassword';

    export default {
        name: "Dashboard",
        components: {
            Sidebar,
            MyCourses,
            Bookings,
            Profile,
            UserAccount,
            ModifyEmail,
            ModifyPassword
        },
        props: {
            'companyId': String,
            'userId': String
        },
        data() {
            return {
                error: null,
                success: null,
                dismissSecs: 3,
                dismissCountDownError: 0,
                dismissCountDownSuccess: 0,
                sidebarIndex: 0,
                modify: null,
                courses: [],
                sports: []
            }
        },
        mounted() {
            window.onhashchange = () => {
                if (window.location.hash === '#corsi') {
                    this.modify = null;
                    this.sidebarIndex = 0;
                }
                if (window.location.hash === '#prenotazioni') {
                    this.modify = null;
                    this.sidebarIndex = 1;
                }
                if (window.location.hash === '#profilo') {
                    this.modify = null;
                    this.sidebarIndex = 2;
                }
                if (window.location.hash === '#account') {
                    this.modify = null;
                    this.sidebarIndex = 3;
                }
                if (window.location.hash === '#email') {
                    this.sidebarIndex = 3;
                    this.modify = 'email';
                }
                if (window.location.hash === '#password') {
                    this.sidebarIndex = 3;
                    this.modify = 'password';
                }
            }
            this.initCourses();
            this.setSidebarIndex();
        },
        watch: {
            sidebarIndex() {
                if (this.sidebarIndex === 0) {
                    this.modify = null;
                    window.location.hash = '#corsi';
                }
                if (this.sidebarIndex === 1) {
                    this.modify = null;
                    window.location.hash = '#prenotazioni';
                }
                if (this.sidebarIndex === 2) {
                    this.modify = null;
                    window.location.hash = '#profilo';
                }
                if (this.sidebarIndex === 3) {
                    window.location.hash = '#account';
                    if (this.modify === 'email') {
                        window.location.hash = '#email';
                    }
                    if (this.modify === 'password') {
                        window.location.hash = '#password';
                    }
                }
            }
        },
        methods: {
            setSidebarIndex() {
                if (window.location.hash === '#corsi') {
                    this.sidebarIndex = 0;
                }
                if (window.location.hash === '#prenotazioni') {
                    this.sidebarIndex = 1;
                }
                if (window.location.hash === '#profilo') {
                    this.sidebarIndex = 2;
                }
                if (window.location.hash === '#account') {
                    this.sidebarIndex = 3;
                }
                if (window.location.hash === '#email') {
                    this.sidebarIndex = 3;
                    this.modify = 'email';
                }
                if (window.location.hash === '#password') {
                    this.sidebarIndex = 3;
                    this.modify = 'password';
                }
            },
            viewCompanies() {
                window.location.href = "/societa_sportive";
            },
            goTo(index) {
                this.sidebarIndex = parseInt(index);
            },
            initCourses() {
                axios({
                    method: 'get',
                    url: '/companies/' + this.companyId + '/courses'
                }).then((response) => {
                    this.courses = response.data.courses;
                    this.sports = response.data.sports;
                }).catch((error) => {
                    this.initError('Internal server error');
                });
            },
            modifyEmail() {
                this.modify = 'email';
                window.location.hash = '#email';
            },
            modifyPassword() {
                this.modify = 'password';
                window.location.hash = '#password';
            },
            resetModify() {
                this.modify = null;
                window.location.hash = '#account';
            },
            initError(message) {
                window.alerts.error(message);
            },
            initSuccess(message) {
                window.alerts.success(message);
            },
            countDownErrorChanged (dismissCountDown) {
                this.dismissCountDownError = dismissCountDown
            },
            countDownSuccessChanged (dismissCountDown) {
                this.dismissCountDownSuccess = dismissCountDown
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>