import {createApp} from 'vue'
import Index from './components/Index.vue'

import router from "./router.js";

import './bootstrap';

import BootstrapVue3 from "bootstrap-vue-3";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";

const app = createApp({
    el: '#app',
    components:{
        'index': Index,
    }
});

app.use(router)
app.use(BootstrapVue3);
app.mount('#app');
