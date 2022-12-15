<template>
    <div class="navbar bg-base-100 fixed border-b  z-10">
        <div class="navbar-start">
            <img class="w-1/6" src="https://www.primeinsuranceghana.com/img/logo.png" alt="Prime Insurance"/>
        </div>
        <div class="navbar-center">
            <router-link to="/" class="text-4xl text-red-600 font-bold">Mentor Mentee</router-link>
        </div>
        <div class="navbar-end">
            <button v-if="!authenticated" @click.prevent="$router.push({name:'login'})" class=" bg-red-500 mr-2 text-white px-5 py-2 rounded-md">
                Login
            </button>
            <button v-if="authenticated && $route.name !== 'admin-home'" @click.prevent="$router.push({name:'admin-home'})" class=" bg-red-500 mr-2 text-white px-5 py-2 rounded-md">
                Administrator
            </button>
            <button v-if="authenticated && $route.name !== 'admin-home'" @click.prevent="startShuffle" :disabled="processing" class="  bg-red-500 mr-2 text-white px-5 py-2 rounded-md" :class="{'bg-slate-200':processing}">
               Shuffle
            </button>
            <button v-if="authenticated" @click.prevent="reset" class=" bg-red-500 mr-2 text-white px-5 py-2 rounded-md">
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
    props:{
        processing:Boolean
    },
    name: "Navbar",
    setup(props) {
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
