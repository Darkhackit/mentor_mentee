<script setup>
import {computed,ref, defineProps,defineEmits} from "vue";
import {useStore} from "vuex";
import {useRoute} from "vue-router";
const emit = defineEmits(['displayForm','deletePost'])

const store = useStore()
const route = useRoute()
const post_pics = computed(() => "/post_files/")
const profile_pic = computed(() => "/profile/")
const user = computed(() => store.getters["auth/mentee"])
const token = computed(() => store.getters["auth/mentee_token"])
const form = ref({
    body:'',
    image:'',
    post_id: ""
})
const errors = ref({})

const props = defineProps(['post'])
const displayForm = (id) => {
    emit('displayForm',id)
}
const deletePost = (id) => {
    emit('deletePost',id)
}

</script>
<template>
    <div class="card mt-10 w-full bg-base-100 shadow-xl">
        <div class="p-3">
            <div class="avatar cursor-pointer">
                <div class="w-8 rounded" @click.prevent="$router.push({name:'mentee-profile',params:{mentee_id:props.post.mentee.id}})">
                    <img class="rounded-full" :src="profile_pic + props.post.mentee.image" alt="Tailwind-CSS-Avatar-component" />
                </div>
                <span @click.prevent="$router.push({name:'mentee-profile',params:{mentee_id:props.post.mentee.id}})" class=" pl-5 text-base uppercase font-semibold">{{props.post.mentee.name}}</span>
                <span class="pl-4 font-medium">{{props.post.created_at}}</span>
                <span v-if="user.id === props.post.mentee.id" @click.prevent="displayForm(props.post.id)" class="pl-72 dropdown font-medium float-right"><font-awesome-icon icon="fa-solid fa-pen-to-square" /></span>
                <span v-if="user.id === props.post.mentee.id" @click.prevent="deletePost(props.post.id)" class="pl-2 dropdown font-medium float-right"><font-awesome-icon icon="fa-solid fa-trash" /></span>
            </div>
            <p>{{ props.post.body.length <= 150 ? props.post.body.substring(0,150) : `${props.post.body.substring(0,150)}...` }} <router-link :to="{name:'single-post',params:{post_id:props.post.id}}" v-if="props.post.body.length >= 150" class="text-black text-sm font-bold">read more</router-link> </p>
        </div>
        <div class="flex flex-col p-2" v-if="props.post.image">

            <img :src="post_pics + props.post.image" alt="Shoes" />
        </div>
        <div class="divider"></div>
        <div class="card-actions justify-between">
            <button class="btn btn-ghost gap-2">
<!--                <font-awesome-icon color="blue" size="2xl" icon="fa-solid fa-thumbs-up" />-->
<!--                <div class="badge">+99</div>-->
            </button>
            <router-link :to="{name:'single-post',params:{post_id:props.post.id}}" class="btn btn-ghost gap-2">
                <font-awesome-icon  size="2xl"  icon="fa-solid fa-comment" />
                <div class="badge">{{props.post.comments_count}}</div>
            </router-link>
            <button class="btn btn-ghost gap-2">
<!--                <font-awesome-icon color="red" size="2xl" icon="fa-solid fa-thumbs-down" />-->
<!--                <div class="badge">+99</div>-->
            </button>
        </div>
    </div>
    <!-- Put this part before </body> tag -->

</template>

<style scoped>

</style>
