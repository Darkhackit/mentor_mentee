<script setup>
import {computed, ref} from 'vue'
import {useRoute} from 'vue-router'
import {useStore} from "vuex";
import RightSideBar from "../../components/posts/RightSideBar.vue";
import TopPost from "../../components/posts/TopPost.vue";

const store = useStore();
const route = useRoute()
const token = computed(() => store.getters["auth/mentee_token"])





</script>
<template>
     <div class="pt-16 container px-10">
         <section class="flex flex-col  pt-10 pb-20">
             <div class="grid grid-cols-12">
                 <div class="col-start-1 col-span-3 p-5 fixed">
                       <right-side-bar/>
                 </div>
                 <div class="col-start-4 col-span-6 p-5 ">
                     <router-view />
                 </div>
                 <div class="col-start-10 col-span-3 p-5 ">
                     <top-post />
                 </div>

             </div>
         </section>
     </div>
</template>

<style scoped>

</style>
