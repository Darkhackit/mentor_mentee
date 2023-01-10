<script setup>
import {useRoute} from "vue-router";
import {useStore} from "vuex";
import {computed, onMounted, ref} from "vue";
import axios from "axios";

const route = useRoute()
const store = useStore()

const user = computed(() => store.getters['auth/mentee'])
const token = computed(() => store.getters['auth/mentee_token'])
const pics = computed(() => '/profile/')
const groupMembers = ref([])

const getGroupMembers = async () => {
    try {
        let response = await axios.get(`/api/mentor/group-members/${route.params.id}`,{headers:{Authorization: 'Bearer ' + token.value}})
        groupMembers.value = response.data
    }catch (e) {
        console.log(e.response)
    }
}


onMounted(async () => {
    console.log(token)
    await getGroupMembers()
})



</script>
<template>
    <div class="">
        <div class="avatar cursor-pointer" @click.prevent="$router.push({name:'mentee-profile',params:{mentee_id:user?.id}})">
            <div class="w-8 rounded">
                <img class="rounded-full" :src="pics + user?.image" alt="Tailwind-CSS-Avatar-component" />
            </div>
            <span class=" pl-5 text-base uppercase font-semibold">{{user?.name}}</span>
        </div>
    </div>
    <div class="divider"></div>
    <p class="text-sm text-neutral-600">Group Members</p>
    <div class="card w-full bg-base-100 shadow" v-for="groupMember in groupMembers" :key="groupMember.id">
        <div class="card-body cursor-pointer" @click.prevent="$router.push({name:'mentee-profile',params:{mentee_id:groupMember.id}})">
            <div class="avatar">
                <div class="w-8 rounded">
                    <img class="rounded-full" :src="pics + groupMember.image" alt="Tailwind-CSS-Avatar-component" />
                </div>
                <span class=" pl-5 text-base uppercase font-semibold">{{groupMember.name}}</span>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
