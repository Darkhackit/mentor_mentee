<template>
    <div class="card sm:card-side bg-base-100 shadow-xl">
        <figure @click.prevent="$router.push('/')"><img src="https://www.primeinsuranceghana.com/img/logo.png" alt="Prime Insurance"/></figure>
        <div class="card-body">
            <h2 class="text-2xl font-bold text-center text-red-600">Welcome Back Administrator!</h2>
            <div class="form-control w-full ">
                <label class="label">
                    <span class="label-text">Enter Email</span>
                </label>
                <input type="text" @input="clearErrors" v-model="form.email" :class="{'border-red-600':errors.email}" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
                                <label class="label" v-if="errors.email">
                                    <span class="text-red-500 label-text-alt">{{errors.email[0]}}</span>
                                </label>
            </div>
            <div class="form-control w-full ">
                <label class="label">
                    <span class="label-text">Enter Password</span>
                </label>
                <input @input="clearErrors" v-model="form.password" :class="{'border-red-600':errors.password}" type="password" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
                                <label class="label" v-if="errors.password">
                                    <span class="text-red-500 label-text-alt">{{errors.password[0]}}</span>
                                </label>
            </div>
            <div class="card-actions justify-center">
                <button @click.prevent="login" class="btn btn-block  bg-red-600 hover:bg-red-600 border-amber-900">Login</button>
            </div>
        </div>
    </div>

</template>

<script>
import {mapMutations} from "vuex";
import axios from "axios";

export default {
    name: "Admin-Login",
    data() {
        return {
            form: {
                email:'',
                password:''
            },
            errors:[]
        }
    },
    methods: {
        ...mapMutations({
            mutateUser:'auth/SET_USER',
            mutateToken:'auth/SET_TOKEN',
            mutateAuth:'auth/SET_AUTHENTICATION'
        }),
        async login () {
            try {
                let response = await axios.post('/api/auth/login',this.form)
                console.log(response.data)
                this.mutateUser(response.data.user)
                this.mutateToken(response.data.authorisation.token)
                this.mutateAuth(true)
                window.localStorage.setItem('prime_mentor',response.data.authorisation.token)
                await this.$router.push({name:'admin-home'})
            }catch (e) {
                console.log(e.response)
                this.errors = e.response.data.errors
            }
        },
        clearErrors() {
            this.errors = []
        }
    }
}
</script>

<style scoped>

</style>
