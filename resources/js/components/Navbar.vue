<template>
    <div class="navbar bg-base-100 fixed border-b border-red-600 z-10">
        <div class="navbar-start">

        </div>
        <div class="navbar-center">

            <img class="w-1/4" src="https://www.primeinsuranceghana.com/img/logo.png" alt="Prime Insurance">
            <router-link to="/" class="btn btn-ghost normal-case text-4xl text-red-600">Mentor Mentee 2.0</router-link>
        </div>
        <div class="navbar-end">
            <button v-if="!authenticated" @click.prevent="$router.push({name:'login'})" class="btn bg-red-500 mr-2">
                Login
            </button>
            <button v-if="authenticated && $route.name !== 'admin-home'" @click.prevent="$router.push({name:'admin-home'})" class="btn bg-red-500 mr-2">
                Administrator
            </button>
            <button v-if="authenticated && $route.name !== 'admin-home'" @click.prevent="startShuffle" class="btn bg-red-500 mr-2">
               Shuffle
            </button>
            <button v-if="authenticated" @click.prevent="reset" class="btn bg-red-500">
                Reset
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {useToast} from "vue-toastification";
import {mapGetters} from 'vuex'
export default {
    name: "Navbar",
    setup() {
        const toast = useToast();
        return { toast }
    },
    computed: {
        ...mapGetters({
            authenticated : 'auth/authenticated'
        })
    },
    methods:{
        startShuffle() {
            this.$emit('startShuffle')
        },
     async   reset() {
           if (confirm('Are you sure you want to reset')) {
               await axios.post('/api/reset')
               this.toast.success("Application has been reset successfully");
               this.$emit('reload')
           }
        }
    }
}
</script>

<style scoped>

</style>
