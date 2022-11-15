import axios from "axios";


const state = {
    authenticated:false,
    user:null,
    token:window.localStorage.getItem('prime_mentor') || null
}

const mutations = {
    SET_AUTHENTICATION:(state,payload) => {
        state.authenticated = payload
    },
    SET_USER:(state,payload) => {
        state.user = payload
    },
    SET_TOKEN:(state,payload) => {
        state.token = payload
    }
}

const actions = {
    refresh:async ({commit}) => {
        try {
            let response = await axios.get('/api/auth/refresh',{headers:{Authorization: 'Bearer ' + state.token}})
            commit('SET_USER',response.data.user)
            commit('SET_AUTHENTICATION',true)
            commit('SET_TOKEN',response.data.authorisation.token)
            console.log((await response).data)
        }catch (e) {

        }
    }
}

const getters = {
    authenticated : state => state.authenticated,
    user:state => state.user,
    token: state => state.token
}


export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}
