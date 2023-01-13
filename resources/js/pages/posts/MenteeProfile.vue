<script setup>
import Post from "../../components/posts/Post.vue";
import {computed, onBeforeMount, ref,watch} from 'vue'
import axios from "axios";
import {useRoute} from "vue-router";
import {useStore} from "vuex";

const profile = ref({})
const route = useRoute()
const store = useStore()


const token = computed(() => store.getters['auth/mentee_token'])
const user = computed(() => store.getters['auth/mentee'])
const profilePic = computed(() => '/profile/')

const getProfile = async () => {
    try {
        let response = await axios.get(`/api/edit/${route.params.mentee_id}`,{headers:{Authorization: 'Bearer ' + token.value}})
        console.log((await response.data))
        profile.value = (await response.data)
    }catch (e) {
        console.log(e)
    }
}

watch(() => route.params.mentee_id,async () => {
    await getProfile()
})

onBeforeMount(async () => {
    await getProfile();
})
const message = () => {
    alert('Chat system coming soon')
}

</script>
<template>
    <div class="">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-3xl font-bold">{{profile?.name}}</h1>
                <div class="avatar">
                    <div class="w-24 rounded-full">
                        <img :src="profilePic + profile?.image" />
                    </div>
                </div>
                <p class="py-6">Head Office</p>
                <button v-if="user?.id === profile?.id" class="btn btn-danger">Change Password</button>
                <button v-if="user?.id !== profile?.id" @click.prevent="message" class="btn btn-danger">Message</button>
            </div>
        </div>
    </div>
    <div class="divider"></div>
    <div class="flex justify-between">
       <div>
           <p>{{profile?.post_count}} post(s)</p>
       </div>
      <div>
          <p>0 post(s)</p>
      </div>
    </div>

    <post v-for="post in profile?.posts" :key="post.id" :post="post" />
</template>

<style scoped>

</style>
