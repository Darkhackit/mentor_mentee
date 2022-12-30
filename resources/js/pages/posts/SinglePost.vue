<script setup>
import {useRouter,useRoute} from "vue-router";
import {useStore} from "vuex";
import axios from "axios";
import {ref, computed, watch, onBeforeMount} from 'vue'


const router = useRouter()
const route = useRoute()
const store = useStore()

const post = ref({})
const token = computed(() => store.getters["auth/mentee_token"])
const user = computed(() => store.getters["auth/mentee"])
const show = (id) => {
    let ids = document.getElementById(id)
    if (ids.classList.contains("hidden")) {
        ids.classList.remove("hidden")
    }else {
        ids.classList.add("hidden")
    }

}
const details = ref({
    mentor:route.params.id,
    post_id:route.params.post_id
})
const comment_details = ref({
    post_id:route.params.post_id,
    body:''
})
const getSinglePost = async () => {
    try {
        let response = await axios.post(`/api/get_single_post`,details.value,{headers:{Authorization: 'Bearer ' + token.value}})
        console.log(response)
        post.value = (await response.data)
    }catch (e) {
        console.log(e)
    }
}
const profile_pic = computed(() => "/profile/")
const post_pics = computed(() => "/post_files/")
const goBack = () => {
    router.back()
}
const comment =  async () => {
    try {
        let response = await axios.post('/api/post-comment',comment_details.value,{headers:{Authorization: 'Bearer ' + token.value}})
        console.log(response)
        comment_details.value.body = ""
        post.value = response.data
    }catch (e) {
        console.log(e.response)
    }
}
watch(() => route.params.post_id,async () => {
    console.log('Hi')
    details.value.post_id = route.params.post_id
    await getSinglePost()
})

onBeforeMount(async () => {
    await getSinglePost()
})
</script>
<template>
    <div class="card w-full bg-base-100 shadow">
        <div class="card-body ">
            <div class="float-left cursor-pointer" @click.prevent="goBack">
                <font-awesome-icon color="red" size="lg" icon=" fa-solid fa-arrow-left" /> <span class="ml-4 ">Back</span>
            </div>

        </div>
    </div>
    <div class="card mt-10 w-full bg-base-100 shadow">
        <div class="p-3">
            <div class="avatar">
                <div class="w-8 rounded">
                    <img class="rounded-full" :src="profile_pic + post.mentee?.image" alt="Tailwind-CSS-Avatar-component" />
                </div>
                <span class=" pl-5 text-base uppercase font-semibold">{{ post.mentee?.name}}</span>
                <span class="px-5 font-medium">{{post?.created_at}}</span>
            </div>
            <p>{{post?.body}}</p>
        </div>
        <div class="flex flex-col p-2">

            <img :src="post_pics +  post?.image" alt="Shoes" />
        </div>
        <div class="divider"></div>
        <div class="card-actions justify-between">
            <button class="btn btn-ghost gap-2">
                <font-awesome-icon color="blue" size="2xl" icon="fa-solid fa-thumbs-up" />
                <div class="badge">+99</div>
            </button>
            <button class="btn btn-ghost gap-2">
                <font-awesome-icon  size="2xl"  icon="fa-solid fa-comment" />
                <div class="badge">{{post?.comments_count}}</div>
            </button>
            <button class="btn btn-ghost gap-2">
                <font-awesome-icon color="red" size="2xl" icon="fa-solid fa-thumbs-down" />
                <div class="badge">+99</div>
            </button>
        </div>
    </div>
    <div class="w-full mt-8">
        <div class="avatar">
            <div class="w-8 rounded">
                <img class="rounded-full" :src="profile_pic + user.image" alt="Tailwind-CSS-Avatar-component" />
            </div>
            <form>
                <textarea cols="70" @keyup.enter="comment" v-model="comment_details.body"  placeholder="Type here" class="input ml-3 bg-white  w-full" ></textarea>
            </form>
        </div>
    </div>
    <div v-for="comment in post?.comments">
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                    <img :src="profile_pic + comment.mentee.image" />
                </div>
            </div>
            <div class="chat-header">
                {{ comment.mentee.name }}
                <time class="text-xs opacity-50">{{comment.created_at}}</time>
            </div>
            <div class="chat-bubble">{{comment.body}}</div>
            <div class="chat-footer opacity-50 cursor-pointer">
                 <span @click="show(comment.id)">reply</span>
                <div class="avatar hidden ease-in duration-300" :id="comment.id">
                    <div class="w-8 rounded">
                        <img class="rounded-full" :src="profile_pic + user.image" alt="Tailwind-CSS-Avatar-component" />
                    </div>
                    <form>
                        <textarea cols="70"   placeholder="Type here" class="input ml-3 bg-white  w-full" ></textarea>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
