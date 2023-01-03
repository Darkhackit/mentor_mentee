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
const user = computed(() => store.getters["auth/mentee"])
const form = ref({
    body:'',
    image:'',
    post_id: ""
})
const errors = ref({})

const getPost = async () => {
    try {
        let response = await axios.get('/api/get-posts',{headers:{Authorization: 'Bearer ' + token.value}})
        console.log(response)
        posts.value = response.data.data
    }catch (e) {
        console.log(e.response)
    }
}
const displayForm = async (id) => {
    try {
        form.value.body = ""
        form.value.post_id = ""
        let response = await axios.post('/api/edit-post',{id:id},{headers:{Authorization: 'Bearer ' + token.value}})
        form.value.body = response.data.body
        form.value.post_id = response.data.id
        document.getElementById('my-modal-4').checked = true
        console.log(response)
    }catch (e) {
        console.log(e)
    }

}
const editPost = async () => {
    let postForm = new FormData();
    postForm.append('body',form.value.body)
    postForm.append('image',form.value.image)
    postForm.append('post_id',form.value.post_id)
    try {
        let response = await  axios.post('/api/update-post',postForm,{headers:{Authorization: 'Bearer ' + token.value}})
        console.log(response)
        await getPost()
        document.getElementById('my-modal-4').checked = false
    }catch (e) {

    }
}
const deletePost = async (id) => {
    if (window.confirm("Are you sure ?")) {
        try {
            let response = await  axios.post('/api/delete-post',{id:id},{headers:{Authorization: 'Bearer ' + token.value}})
            console.log(response)
            await getPost()
        }catch (e) {

        }
    }
}
const getImage = (e) => {
    form.value.image = e.target.files[0]
}
const clearError = () => {
    errors.value = []
}
onMounted(async () => {
    await getPost()
})
</script>
<template>
    <create-post @getPost="getPost" />
    <post v-for="post in posts" :key="post.id" :post="post" @displayForm="displayForm" @deletePost="deletePost" />
    <input type="checkbox" id="my-modal-4" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative">
            <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <form @submit.prevent="editPost">
                <div>
                    <p class="mb-10 text-xl">Edit Post</p>
                    <textarea @keyup="clearError" v-model.trim="form.body" class="textarea w-full" :class="{'border-r-error':errors.body}" rows="5" :placeholder="`Type Something....${user.name}`"></textarea>
                    <p v-if="errors.body" class="text-red-500">Please type something</p>
                </div>
                <div class="mt-3">
                    <input @change="getImage" type="file" class="file-input w-full " multiple />
                </div>
                <div class="mt-3 float-right">
                    <button type="submit"  class="btn">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
