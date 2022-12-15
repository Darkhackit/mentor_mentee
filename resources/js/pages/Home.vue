<template>
    <navbar @startShuffle="shuffle" @reload="reload" :processing="processing"/>
   <div class="container mx-auto pt-20 h-screen px-2">
       <section>
                   <div class="mt-10">
                    <div>
                        <h1 class="text-red-500 font-bold text-xl px-20 mb-10">Mentors</h1>
                    </div>
                       <div class="flex flex-row space-x-3 p-3" :class="`grid-cols-${mentors.length}`">
                               <div class="card card-compact w-full bg-base-100" v-for="mentor in mentors" :key="mentor.id">
                               <div class="flex flex-col space-y-5 justify-center items-center">
                                    <div class="h-48 w-48 rounded-full"><img class="object-cover object-top rounded-full h-48 w-48" :src="getPics + mentor.image" alt="Shoes" /></div>
                                    <h2 class="text-2xl uppercase font-bold text-center">{{mentor.name}}</h2>
                                    <p class="text-red-500 text-sm text-center">{{mentor.postion||'Position'}}</p>
                               </div>
                                   <div class="card-body px-10 mt-10 bg-slate-100 rounded-md">
                                       <div :key="mente.id" class="divide-y-4 divide-red-600" v-for="mente in mentor.mentees" v-if="mentor.mentees.length>=1">
                                           <div>
                                               <div class="flex justify-between pb-2">
                                                   <span class="float-left text-base uppercase font-semibold">{{mente.name}}</span>
                                                   <div class="avatar">
                                                       <div class="w-8 rounded">
                                                           <img class="rounded-full" :src="getPics + mente.image" alt="Tailwind-CSS-Avatar-component" />
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                    <div class="text-center" v-else>
                                        <p>No mentees Available</p>
                                    </div>
                                   </div>
                               </div>
                       </div>
                   </div>
       </section>
       <div class="w-full  bg-white  min-w-0 mt-20 px-20">
        <div>
            <h1 class="text-red-500 font-bold text-xl mb-10">Mentees</h1>
        </div>
           <div class="grid grid-cols-6 gap-10">
               <div class="flex-none py-6 px-3 first:pl-6 last:pr-6 border border-slate-200 rounded-md" v-for="mentee in mentees" :key="mentee.id">
                   <div class="flex flex-col items-center justify-center gap-3">
                       <img class="w-14 h-14 object-cover rounded-full" :src="getPics + mentee.image">
                       <p class="text-black capitalize text-center">{{ mentee.name }}</p>
                   </div>
               </div>
           </div>
       </div>
   </div>

</template>

<script>
import axios from "axios";
import Navbar from "../components/Navbar.vue";
export default {
    name: "Home",
    components: {Navbar},
    data() {
        return {
            mentees:[],
            mentors:[],
            timer:'',
            id:null,
            processing:false
        }
    },
    computed: {
        getPics() {
            return '/profile/'
        }
    },
    methods: {
      async  mentee_without_id() {
          try {
              let response = await axios.get('/api/mentee_without_id')
              this.mentees = response.data
          }catch (e) {
              console.log(e.response)
          }
      },
      async  shuffleStart() {
          try {
              let response = await axios.post('/api/shuffle',{current : this.id})

              if (response.data === true) {
                  return this.clearTimer()
                  this.$toast.success("Application has been reset successfully");
              }
              this.id = response.data
              console.log(response)
          }catch (e) {
              console.log(e.response)
          }
        },
      async  getStarted() {
          this.processing = true;
          await this.shuffleStart()
          await this.get_mentees()
          await this.mentee_without_id()
        },
        async reload() {
            await this.get_mentees()
            await this.mentee_without_id()
        },
        shuffle() {
           this.timer = setInterval(this.getStarted,5000)
        },
        async get_mentees() {
          try {
              let response = await axios.get('/api/get_mentees')
              this.mentors = (await response).data

          }catch (e) {
              console.log(e.response)
          }
        },
        clearTimer() {
          clearInterval(this.timer)
          this.processing = false
        }
    },
    async mounted() {
        await this.mentee_without_id()
        await this.get_mentees()
    }
}
</script>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
