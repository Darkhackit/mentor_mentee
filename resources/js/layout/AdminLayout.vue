<template>
    <navbar />
    <router-view />
</template>

<script>
import Navbar from "../components/Navbar.vue";
import {mapGetters} from "vuex";
export default {
    name: "AdminLayout",
    components: {Navbar},
    computed: {
        ...mapGetters({
            authenticated:'auth/authenticated'
        })
    },
    mounted() {
        if(!this.authenticated) {
            this.$router.push({name:'login'})
        }

    }
}
</script>

<style scoped>

</style>
