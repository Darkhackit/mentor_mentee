import axios from "axios";


const state = {
    authenticated:false,
    auth_mentee:false,
    mentee:'',
    mentee_token:window.localStorage.getItem('prime_mentee') || null,
    user:null,
    token:window.localStorage.getItem('prime_mentor') || null
}

const mutations = {
    SET_AUTHENTICATION:(state,payload) => {
        state.authenticated = payload
    },
    SET_MENTEE_AUTHENTICATION:(state,payload) => {
        state.auth_mentee = payload
    },
    SET_USER:(state,payload) => {
        state.user = payload
    },
    SET_MENTEE:(state,payload) => {
        state.mentee = payload
    },
    SET_TOKEN:(state,payload) => {
        state.token = payload
    },
    SET_MENTEE_TOKEN:(state,payload) => {
        state.mentee_token = payload
    }
}

const actions = {
    refresh:async ({commit}) => {
        try {
            let response = await axios.get('/api/auth/refresh',{headers:{Authorization: 'Bearer ' + state.token}})
            commit('SET_USER',response.data.user)
            commit('SET_AUTHENTICATION',true)
            commit('SET_TOKEN',response.data.authorisation.token)
            console.log(response)

        }catch (e) {
            console.log(e.response)
        }

        try {
            let mentee = await axios.get('/api/mentee/refresh',{headers:{Authorization: 'Bearer ' + state.mentee_token}})
            commit('SET_MENTEE_AUTHENTICATION',true)
            commit('SET_MENTEE',mentee.data.user)
            commit('SET_MENTEE_TOKEN',mentee.data.authorisation.token)
            console.log(mentee)
        }catch (e) {
            console.log(e.response)
        }
    }
}

const getters = {
    authenticated : state => state.authenticated,
    user:state => state.user,
    token: state => state.token,
    auth_mentee: state => state.auth_mentee,
    mentee: state => state.mentee,
    mentee_token: state => state.mentee_token,
}


export default {
    namespaced:true,
    state,
    mutations,
    actions,
    getters
}
