import {createRouter, createWebHistory} from "vue-router";
import HomeLayout from "../layout/HomeLayout.vue";
import AuthLayout from "../layout/AuthLayout.vue";
import Login from "../pages/auth/Login.vue";
import AdminLayout from "../layout/AdminLayout.vue";
import AdminHome from "../pages/AdminHome.vue";
import Home from "../pages/Home.vue";


const routes = [
    {
                path:'/',
                name:'home',
                component: Home
    },
    {
        path: '/auth',
        component: AuthLayout,
        children:[
            {
                path:'login',
                name:'login',
                component:Login
            }
        ]
    },
    {
        path: '/admin',
        component: AdminLayout,
        children: [
            {
                path: 'home',
                name:'admin-home',
                component:AdminHome
            }
        ]
    }
]

const router = createRouter({
    history:createWebHistory(),
    routes
})

export default router
