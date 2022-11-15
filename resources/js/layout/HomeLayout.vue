<template>
      <navbar />
    <router-view />
</template>

<script>
import Navbar from "../components/Navbar.vue";
export default {
    name: "HomeLayout",
    components: {Navbar}
}
</script>

<style scoped>

</style>
