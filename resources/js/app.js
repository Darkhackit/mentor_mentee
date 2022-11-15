import './bootstrap';

import {createApp} from "vue";
import App from "./App.vue";
import VueGoodTablePlugin from 'vue-good-table-next';
import Toast from "vue-toastification";

const options = {
    // You can set your default options here
};


import 'vue-good-table-next/dist/vue-good-table-next.css'
import "vue-toastification/dist/index.css";
import router from "./routes";
import store from "./store";

store.dispatch('auth/refresh').then(() => {
    createApp(App).use(router).use(Toast,options).use(store).use(VueGoodTablePlugin).mount('#app')
})

