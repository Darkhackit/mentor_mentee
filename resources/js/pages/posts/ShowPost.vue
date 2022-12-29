<script setup>
import CreatePost from "../../components/posts/CreatePost.vue";
import Post from "../../components/posts/Post.vue";
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import {useStore} from "vuex";
import {useRoute} from "vue-router";



const posts = ref([])
const emit = defineEmits([''])
const store = useStore();
const route = useRoute()
const token = computed(() => store.getters["auth/mentee_token"])
const getPost = async () => {
    try {
        let response = await axios.get('/api/get-posts',{headers:{Authorization: 'Bearer ' + token.value}})
        console.log(response)
        posts.value = response.data.data
    }catch (e) {
        console.log(e.response)
    }
}

onMounted(async () => {
    await getPost()
})
</script>
<template>
    <create-post @getPost="getPost" />
    <post v-for="post in posts" :key="post.id" :post="post" />
</template>

<style scoped>

</style>
