<script>

    export default {
        props: ['userprop', 'purchaseprop', 'purchaseelementsprop'],
        data() {
            return {
                user: {},
                purchase: {},
                purchaseElements: []
            }
        },
        mounted() {
            this.user = this.userprop;
            this.purchase = this.purchaseprop;
            this.purchaseElements = this.purchaseelementsprop;
        },
        methods: {
            isSubscription(buyable_type) {
                return buyable_type == "App\\CourseSubscription"
            },
            isLessonPackage(buyable_type) {
                return buyable_type == "App\\LessonPackage"
            },
        },
    }
</script>