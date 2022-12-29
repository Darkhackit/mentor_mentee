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
                component: () => import('../pages/Home.vue')
    },
    {
        path: '/auth',
        component: () => import('../layout/AuthLayout.vue'),
        children:[
            {
                path:'login',
                name:'login',
                component:() => import('../pages/auth/Login.vue')
            },
            {
                path:'login/:id',
                name:'mentee-login',
                component:() => import('../pages/auth/MenteeLogin.vue')
            }
        ]
    },
    {
        path: '/community',
        component: () => import('../layout/PostLayout.vue'),
        children:[
            {
                path: ':id',
                component: () => import('../pages/posts/Index.vue'),
                children:[
                    {
                        path:'',
                        component:() => import('../pages/posts/ShowPost.vue'),
                        name:'mentor_post',
                    },

                    {
                        path:'post/:post_id',
                        component:() => import('../pages/posts/SinglePost.vue'),
                        name:'single-post'
                    }
                ]
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
