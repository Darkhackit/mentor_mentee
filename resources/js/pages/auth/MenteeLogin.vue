
<script setup>
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import {useRoute,useRouter} from 'vue-router';
import {useStore} from "vuex";
const route = useRoute()
const router = useRouter()
const store = useStore()
const errors = ref([])
const form = ref({
    email:'',
    password:'',
    id:''
})
const mentor_name = ref("")
const mentor_image = ref("")
const pics = computed(() => {
    return `/profile/`
})
const getMentor = async () => {
     try {
         let response = await axios.get(`/api/mentor/edit/${route.params.id}`)
         mentor_name.value = response.data.name
         mentor_image.value = response.data.image
         form.value.id = response.data.id
     }catch (e) {
         console.log(e.response)
     }
}
onMounted(async () => {
    await getMentor()
})

const login = async () => {
    try {
        let response = await axios.post(`/api/mentee_login`,form.value)
        store.commit("auth/SET_MENTEE_AUTHENTICATION",true)
        store.commit("auth/SET_MENTEE",response.data.user)
        store.commit("auth/SET_MENTEE_TOKEN",response.data.authorisation.token)
        window.localStorage.setItem('prime_mentee',response.data.authorisation.token)
        await router.push({name:'mentor_post',params:{id:response.data.user.mentor.name}})
    }catch (e) {
        console.log(e.response)
        errors.value = e.response.data.errors
    }

}
const clearErrors = () => {
    errors.value = []
}

</script>
<template>
    <div class="card sm:card-side bg-base-100 shadow-xl">
        <figure @click.prevent="$router.push('/')"><img :src="pics + mentor_image" alt="Prime Insurance"/></figure>
        <div class="card-body">
            <h2 class="text-2xl font-bold text-center text-red-600">{{ mentor_name }}</h2>
            <form @submit.prevent="login">
                <div class="form-control w-full ">
                    <label class="label">
                        <span class="label-text">Enter Email</span>
                    </label>
                    <input type="text" @input="clearErrors" v-model="form.email" :class="{'border-red-600':errors.email}" placeholder="Type here" class="input input-bordered w-full max-w-xs" required/>
                    <label class="label" v-if="errors.email">
                        <span class="text-red-500 label-text-alt">{{errors.email[0]}}</span>
                    </label>
                </div>
                <div class="form-control w-full ">
                    <label class="label">
                        <span class="label-text">Enter Password</span>
                    </label>
                    <input @input="clearErrors" v-model="form.password" :class="{'border-red-600':errors.password}" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" required/>
                    <label class="label" v-if="errors.password">
                        <span class="text-red-500 label-text-alt">{{errors.password[0]}}</span>
                    </label>
                </div>
                <div class="card-actions justify-center mt-5">
                    <button type="submit" class="btn btn-block  bg-red-600 hover:bg-red-600 border-amber-900">Login</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
