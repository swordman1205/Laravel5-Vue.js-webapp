<script>
    import BsDrawer from 'vue-bs-drawer'

    export default {
        name: "Sidebar",
        components: {
            BsDrawer
        },
        props: {
            sidebarIndex: Number
        },
        methods: {
            goTo(index) {
                this.$emit('go-to', index);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .btn-link {
        color: black;
        font-weight: bold;
    }
</style>