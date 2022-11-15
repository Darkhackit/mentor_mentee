<template>
    <div class="flex items-center justify-center h-screen">
 <router-view />
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "AuthLayout",
    computed:{
        ...mapGetters({
            authenticated:'auth/authenticated'
        })
    },
    mounted() {
        if(this.authenticated) {
            this.$router.push({name:'admin-home'})
        }

    }

}
</script>

<style scoped>

</style>
