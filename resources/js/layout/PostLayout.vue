<script setup>
 import {RouterView} from 'vue-router';
 import {useStore} from 'vuex';
 import {useRouter} from 'vue-router'
 import {computed, onMounted} from "vue";
 import axios from "axios";

 const store = useStore();
 const router = useRouter()

 const auth = computed(() => store.getters["auth/auth_mentee"])
 const token = computed(() => store.getters["auth/mentee_token"])
 const user = computed(() => store.getters["auth/mentee"])
 const pic  = computed(() => "/profile/")

 const logout = async () => {
     try {
          await axios.get('/api/mentee/logout',{headers:{Authorization: 'Bearer ' + token.value}})
         store.commit('auth/SET_MENTEE_TOKEN',null)
         store.commit('auth/SET_MENTEE',{})
         store.commit('auth/SET_MENTEE_AUTHENTICATION',false)
          await router.push('/')
     }catch (e) {
         console.log(e.response)
     }
 }

 onMounted(async () => {
     if(auth.value === false) {
         await router.push({name:'login'})
     }
 })

</script>
<template>
    <div>
        <div class="navbar  bg-base-100 fixed border-b border-red-600 z-10">
            <div class="navbar-start" @click.prevent="$router.push({name:'mentor_post',params:{id:$route.params.id}})">
                <img class="w-1/6" src="https://www.primeinsuranceghana.com/img/logo.png" alt="Prime Insurance">
            </div>
            <div class="navbar-center cursor-pointer" @click.prevent="$router.push({name:'mentor_post',params:{id:$route.params.id}})">
                <h2 class="text-xl text-red-500">{{$route.params.id}}</h2>
            </div>
            <div class="navbar-end">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img :src="pic + user.image" />
                        </div>
                    </label>
                    <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                            <a class="justify-between">
                                Profile

                            </a>
                        </li>
                        <li><a>Settings</a></li>
                        <li><a @click.prevent="logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <router-view />
    </div>
</template>
<style scoped>

</style>
