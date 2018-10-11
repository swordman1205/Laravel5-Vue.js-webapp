<script>
    export default {
        props: ['cartelementsprop', 'sportmenprop', 'totalpriceprop'],
        data() {
            return {
                modalShow: false,
                cartElements: [],
                total: 0,
            }
        },
        mounted() {
            this.cartElements = this.cartelementsprop,
            this.total = this.totalpriceprop
        },
        methods: {
            tabNumber(id){
                return id
            },
            isSubscription(buyable_type) {
                return buyable_type == "App\\CourseSubscription"
            },
            isLessonPackage(buyable_type) {
                return buyable_type == "App\\LessonPackage"
            },
            goAhead(to) {
                this.$bus.$emit('go-ahead', to);
            },
            getCartElementId(cartElement) {
                return 'cart-element' + cartElement.id
            },
            remove(elementId, index) {
                var self = this;

                axios({
                    method: 'delete',
                    url: '/carts/' + elementId
                }).then((response) => {
                    self.$bus.$emit('cart-element-removed', elementId);

                    self.cartElements.splice(index);
                    self.total = 0;
                    self.cartElements.forEach(function (cartElement) {
                        self.total += cartElement.buyable.price
                    })
                }).catch((error) => {
                    self.$emit('init-error', 'Delete failed');
                });
            }
        },
    }
</script>