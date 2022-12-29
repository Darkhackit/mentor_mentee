<script setup>
import {computed, ref,defineEmits} from 'vue'
import {useStore} from "vuex";
import {useRoute} from "vue-router";
import axios from "axios";
  const displayForm = () => {
      document.getElementById('my-modal-3').checked = true
  }
  const emit = defineEmits(['getPost'])
  const store = useStore()
  const route = useRoute()
  const form = ref({
      body:'',
      image:'',
      mentor: route.params.id
  })
const errors = ref([])
const token = computed(() => store.getters["auth/mentee_token"])
const user = computed(() => store.getters["auth/mentee"])
const profile_pic = computed(() => "/profile/")
const getImage = (e) => {
    form.value.image = e.target.files[0]
}
const clearError = () => {
      errors.value = []
}
const createPost = async () => {
    let postForm = new FormData();
    postForm.append('body',form.value.body)
    postForm.append('image',form.value.image)
    postForm.append('mentor',form.value.mentor)

    try {
         await axios.post('/api/create-post',postForm,{headers:{Authorization: 'Bearer ' + token.value}})
         emit('getPost')
        document.getElementById('my-modal-3').checked = false
        form.value.body = ""
        form.value.image = ""
    }catch (e) {
        console.log(e.response)
        errors.value = e.response.data.errors
    }
}
</script>
<template>
    <div class="card w-full bg-base-100 shadow" @click.prevent="displayForm">
        <div class="card-body">
            <h2 class="card-title">Post Feed</h2>
            <div class="avatar">
                <div class="w-8 rounded">
                    <img class="rounded-full" :src="profile_pic + user.image" alt="Tailwind-CSS-Avatar-component" />
                </div>
                <input type="text" @focus="displayForm" placeholder="Type here" class="input ml-3  w-full" />
            </div>
            <div class="divider"></div>
            <div class="flex justify-between">
                <button class="btn btn-ghost btn-xs sm:btn-sm md:btn-md lg:btn-lg gap-2">
                    Photo
                    <font-awesome-icon color="green" icon="fa-solid fa-image " size="2x" />
                </button>
                <button class="btn btn-ghost btn-xs sm:btn-sm md:btn-md lg:btn-lg gap-2">
                    Video
                    <font-awesome-icon color="red" icon="fa-solid fa-video-camera" size="2x" />
                </button>
            </div>
        </div>
    </div>

    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="my-modal-3" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative">
            <label for="my-modal-3" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <form @submit.prevent="createPost">
                <div>
                    <p class="mb-10 text-xl">Create Post</p>
                    <textarea @keyup="clearError" v-model.trim="form.body" class="textarea w-full" :class="{'border-r-error':errors.body}" rows="5" :placeholder="`Type Something....${user.name}`"></textarea>
                    <p v-if="errors.body" class="text-red-500">Please type something</p>
                </div>
                <div class="mt-3">
                    <input @change="getImage" type="file" class="file-input w-full " multiple />
                </div>
                <div class="mt-3 float-right">
                    <button type="submit"  class="btn">Add Post</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
