<script>
    export default {

        props: ['sportmenprop','cartelementsprop'],

        data() {
            return {
                friends: [],
                sportsman: {
                    firstName: null,
                    lastName: null,
                    email: null,
                    day: null,
                    month: null,
                    year: null,
                },
                selectedFriend: null,
                cartElements: [],
                days: [
                    {value: undefined, text: 'Giorno'}
                ],
                months: [
                    {value: undefined, text: 'Mese'},
                    {value: 0, text: 'Gennaio'},
                    {value: 1, text: 'Febbraio'},
                    {value: 2, text: 'Marzo'},
                    {value: 3, text: 'Aprile'},
                    {value: 4, text: 'Maggio'},
                    {value: 5, text: 'Giugno'},
                    {value: 6, text: 'Luglio'},
                    {value: 7, text: 'Agosto'},
                    {value: 8, text: 'Settembre'},
                    {value: 9, text: 'Ottobre'},
                    {value: 10, text: 'Novembre'},
                    {value: 11, text: 'Dicembre'}
                ],
                years: [
                    {value: undefined, text: 'Anno'}
                ],
                isTermAccepted: false,
            }
        },
        created () {
            var self = this;

            for (let i = 1; i <= 31; i++) {
                this.days.push({value: i, text: i});
            }
            for (let i = new Date().getFullYear(); i >= 1970; i--) {
                this.years.push({value: i, text: i});
            }
            
            this.$bus.$on('cart-element-removed', function (args) {
                var removedElement;

                self.cartElements.forEach(function (cartElement) {
                    if (cartElement.id == args) {
                        removedElement = cartElement;
                    }
                })

                var index = self.cartElements.indexOf(removedElement);
                self.cartElements.splice(index, 1);
            })
        },
        mounted() {
            this.friends = this.sportmenprop;
            this.friends.forEach(function (friend) {
                friend.name = friend.first_name + ' ' + friend.last_name;
            });
            this.cartElements = this.cartelementsprop;
        },
        methods: {
            goAhead(to) {
                this.$bus.$emit('go-ahead', to);
            },
            isSubscription(buyable_type) {
                return buyable_type == "App\\CourseSubscription"
            },
            isLessonPackage(buyable_type) {
                return buyable_type == "App\\LessonPackage"
            },
        },
        watch: {
            selectedFriend () {
                if (this.selectedFriend !== undefined) {
                    let friend = this.friends.find((friend) => {
                        return friend.id == this.selectedFriend;
                    });

                    this.sportsman = {
                        firstName: friend.first_name,
                        lastName: friend.last_name,
                        email: friend.email,
                        day: friend.day,
                        month: friend.month,
                        year: friend.year
                    };
                }
            },
        },
    }
</script>