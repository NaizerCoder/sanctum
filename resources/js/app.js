import {createApp} from 'vue'
import Index from './components/Index.vue'

import router from "./router.js";

import './bootstrap';

import BootstrapVue3 from "bootstrap-vue-3";
import {BIconPencil} from "bootstrap-icons-vue";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";
// import "bootstrap-icons/font/bootstrap-icons.css"

const app = createApp({
    el: '#app',
    components:{
        'index': Index,
        'BIconPencil': BIconPencil
    }
});

app.use(router)
app.use(BootstrapVue3);
app.mount('#app');
