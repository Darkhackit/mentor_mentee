<script setup>
import {computed, defineProps} from "vue";
import {useStore} from "vuex";

const store = useStore()
const post_pics = computed(() => "/post_files/")
const profile_pic = computed(() => "/profile/")
const user = computed(() => store.getters["auth/mentee"])

const props = defineProps(['post'])
</script>
<template>
    <div class="card mt-10 w-full bg-base-100 shadow-xl">
        <div class="p-3">
            <div class="avatar cursor-pointer" @click.prevent="$router.push({name:'profile-mentee',params:{mentee_id:props.post.mentee.id}})">
                <div class="w-8 rounded">
                    <img class="rounded-full" :src="profile_pic + props.post.mentee.image" alt="Tailwind-CSS-Avatar-component" />
                </div>
                <span class=" pl-5 text-base uppercase font-semibold">{{props.post.mentee.name}}</span>
                <span class="px-5 font-medium">{{props.post.created_at}}</span>
            </div>
            <p>{{ props.post.body.length <= 150 ? props.post.body.substring(0,150) : `${props.post.body.substring(0,150)}...` }} <router-link :to="{name:'single-post',params:{post_id:props.post.id}}" v-if="props.post.body.length >= 150" class="text-black text-sm font-bold">read more</router-link> </p>
        </div>
        <div class="flex flex-col p-2">

            <img :src="post_pics + props.post.image" alt="Shoes" />
        </div>
        <div class="divider"></div>
        <div class="card-actions justify-between">
            <button class="btn btn-ghost gap-2">
                <font-awesome-icon color="blue" size="2xl" icon="fa-solid fa-thumbs-up" />
                <div class="badge">+99</div>
            </button>
            <router-link :to="{name:'single-post',params:{post_id:props.post.id}}" class="btn btn-ghost gap-2">
                <font-awesome-icon  size="2xl"  icon="fa-solid fa-comment" />
                <div class="badge">{{props.post.comments_count}}</div>
            </router-link>
            <button class="btn btn-ghost gap-2">
                <font-awesome-icon color="red" size="2xl" icon="fa-solid fa-thumbs-down" />
                <div class="badge">+99</div>
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
