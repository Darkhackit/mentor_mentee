<script setup>
import {ref} from 'vue'
import {defineProps,computed} from "vue";
import {useStore} from "vuex";
const props = defineProps(['post'])

const store = useStore();
const user = computed(() => store.getters["auth/mentee"])
const post_pics = computed(() => "/post_files/")
const profile_pic = computed(() => "/profile/")
</script>
<template>
    <div>
        <figure class="relative flex flex-col-reverse bg-slate-50 rounded-lg p-6 ">
            <blockquote class="mt-6 text-slate-700 ">
                <p>{{ props.post.body.length <= 100 ? props.post.body.substring(0,100) : `${props.post.body.substring(0,100)}...` }}</p>
            </blockquote>
            <figcaption class="flex items-center space-x-4">
                <img :src="profile_pic + props.post.mentee.image" alt="" class="flex-none w-14 h-14 rounded-full object-cover" loading="lazy" decoding="async">
                <div class="flex-auto"><div class="text-base text-slate-900 font-semibold ">
                    <router-link :to="{name:'single-post',params:{post_id:props.post.id}}" tabindex="0">
                        <span class="absolute inset-0"></span>{{props.post.mentee.name}}</router-link>
                </div>
                </div>
            </figcaption>
        </figure>
    </div>
</template>

<style scoped>

</style>
