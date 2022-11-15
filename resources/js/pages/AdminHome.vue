<template>
  <div class="container mx-auto pt-20 px-4">
      <section class="flex flex-col h-screen  pb-20">
          <div class="grid grid-cols-12">
              <div class="col-start-1 col-span-6">
                  <mentee />
              </div>
              <div class="col-start-7 col-span-6">
                  <mentor />
              </div>
          </div>
      </section>
  </div>
</template>

<script>
import Mentee from "../components/Mentee.vue";
import Mentor from "../components/Mentor.vue";
export default {
    name: "AdminHome",
    components: {Mentor,Mentee}
}
</script>

<style scoped>

</style>
