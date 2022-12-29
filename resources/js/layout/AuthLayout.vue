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
            authenticated:'auth/authenticated',
            mentee_auth:'auth/auth_mentee',
            mentee:'auth/mentee'
        })
    },
    mounted() {
        if (this.mentee_auth){
            this.$router.push({name:'mentor_post',params:{id:this.mentee.mentor.name}})
        }
        if(this.authenticated) {
            this.$router.push({name:'admin-home'})
        }

    }

}
</script>

<style scoped>

</style>
