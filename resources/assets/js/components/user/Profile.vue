<script>
    import Sidebar from './Sidebar';
    import Profile from './Profile';
    import UserReservations from './Reservations';
    import UserAccount from './UserAccount';
    import ModifyEmail from './ModifyEmail';
    import ModifyPassword from './ModifyPassword';


    export default {
        name: "Profile",
        components: {
            Sidebar,
            UserReservations,
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
                courses: []
            }
        },
        mounted() {
            window.onhashchange = () => {
                if (window.location.hash === '#prenotazioni') {
                    this.modify = null;
                    this.sidebarIndex = 0;
                }
                if (window.location.hash === '#aacount') {
                    this.modify = null;
                    this.sidebarIndex = 1;
                }
                if (window.location.hash === '#email') {
                    this.sidebarIndex = 1;
                    this.modify = 'email';
                }
                if (window.location.hash === '#password') {
                    this.sidebarIndex = 1;
                    this.modify = 'password';
                }
            }
            this.setSidebarIndex();
        },
        watch: {
            sidebarIndex() {
                if (this.sidebarIndex === 0) {
                    this.modify = null;
                    window.location.hash = '#prenotazioni';
                }
                if (this.sidebarIndex === 1) {
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
                if (window.location.hash === '#prenotazioni') {
                    this.sidebarIndex = 0;
                }
                if (window.location.hash === '#account') {
                    this.sidebarIndex = 1;
                }
                if (window.location.hash === '#email') {
                    this.sidebarIndex = 1;
                    this.modify = 'email';
                }
                if (window.location.hash === '#password') {
                    this.sidebarIndex = 1;
                    this.modify = 'password';
                }
            },
            goTo(index) {
                this.sidebarIndex = parseInt(index);
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
            countDownErrorChanged(dismissCountDown) {
                this.dismissCountDownError = dismissCountDown
            },
            countDownSuccessChanged(dismissCountDown) {
                this.dismissCountDownSuccess = dismissCountDown
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>