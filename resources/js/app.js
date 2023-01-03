import './bootstrap';

import {createApp} from "vue";
import App from "./App.vue";
import VueGoodTablePlugin from 'vue-good-table-next';
import Toast from "vue-toastification";
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


import { faImage } from '@fortawesome/free-solid-svg-icons'
import { faVideoCamera } from '@fortawesome/free-solid-svg-icons'
import { faThumbsUp } from '@fortawesome/free-solid-svg-icons'
import { faComment } from '@fortawesome/free-solid-svg-icons'
import { faThumbsDown } from '@fortawesome/free-solid-svg-icons'
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
import { faPenToSquare } from '@fortawesome/free-solid-svg-icons'
import { faTrash } from '@fortawesome/free-solid-svg-icons'

library.add(faTrash,faImage,faVideoCamera,faThumbsUp,faComment,faThumbsDown,faArrowLeft,faPenToSquare)
const options = {
    // You can set your default options here
};

import { uniLayerGroupMonochrome, uniCarWash } from 'vue-unicons/dist/icons'
import 'vue-good-table-next/dist/vue-good-table-next.css'
import "vue-toastification/dist/index.css";
import router from "./routes";
import store from "./store";

store.dispatch('auth/refresh').then(() => {
    createApp(App).component('font-awesome-icon', FontAwesomeIcon).use(router).use(Toast,options).use(store).use(VueGoodTablePlugin).mount('#app')
})

